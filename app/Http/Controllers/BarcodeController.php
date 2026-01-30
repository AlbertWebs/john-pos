<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class BarcodeController extends Controller
{
    /**
     * Display items without barcodes
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['category', 'brand'])
            ->where(function($q) {
                $q->whereNull('barcode')
                  ->orWhere('barcode', '');
            })
            ->orderBy('name');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->paginate(50);

        // Get categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('barcodes.index', compact('items', 'categories'));
    }

    /**
     * Generate barcode for a single item
     */
    public function generate(Request $request, Inventory $inventory)
    {
        if ($inventory->barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Item already has a barcode',
            ], 400);
        }

        // Generate barcode based on part number or ID
        $barcode = $this->generateBarcode($inventory);

        $inventory->update(['barcode' => $barcode]);

        return response()->json([
            'success' => true,
            'message' => 'Barcode generated successfully',
            'barcode' => $barcode,
        ]);
    }

    /**
     * Generate barcodes for multiple items
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:inventory,id',
        ]);

        $generated = 0;
        $skipped = 0;

        foreach ($request->item_ids as $itemId) {
            $item = Inventory::find($itemId);
            
            if ($item && !$item->barcode) {
                $barcode = $this->generateBarcode($item);
                $item->update(['barcode' => $barcode]);
                $generated++;
            } else {
                $skipped++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generated} barcode(s), skipped {$skipped} item(s) that already have barcodes",
            'generated' => $generated,
            'skipped' => $skipped,
        ]);
    }

    /**
     * Generate barcode for all items without barcodes
     */
    public function generateAll(Request $request)
    {
        $items = Inventory::whereNull('barcode')
            ->orWhere('barcode', '')
            ->get();

        $generated = 0;

        foreach ($items as $item) {
            $barcode = $this->generateBarcode($item);
            $item->update(['barcode' => $barcode]);
            $generated++;
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generated} barcode(s)",
            'generated' => $generated,
        ]);
    }

    /**
     * Download PDF with barcodes for printing stickers
     */
    public function downloadPDF(Request $request)
    {
        $itemIds = $request->input('item_ids', []);
        
        if (empty($itemIds)) {
            // Get all items with barcodes if none specified
            $items = Inventory::whereNotNull('barcode')
                ->where('barcode', '!=', '')
                ->with(['category', 'brand'])
                ->orderBy('name')
                ->get();
        } else {
            $items = Inventory::whereIn('id', $itemIds)
                ->whereNotNull('barcode')
                ->where('barcode', '!=', '')
                ->with(['category', 'brand'])
                ->orderBy('name')
                ->get();
        }

        if ($items->isEmpty()) {
            return back()->with('error', 'No items with barcodes found to print');
        }

        $html = view('barcodes.pdf', compact('items'))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'barcode-stickers-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate a unique barcode for an item
     */
    private function generateBarcode(Inventory $inventory): string
    {
        // Try to use part number as base, or generate from ID
        $base = $inventory->part_number ?: 'BC' . str_pad($inventory->id, 10, '0', STR_PAD_LEFT);
        
        // Ensure barcode is unique
        $barcode = $base;
        $counter = 1;
        
        while (Inventory::where('barcode', $barcode)->where('id', '!=', $inventory->id)->exists()) {
            $barcode = $base . '-' . $counter;
            $counter++;
        }

        return $barcode;
    }
}
