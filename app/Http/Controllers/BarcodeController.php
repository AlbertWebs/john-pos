<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Picqer\Barcode\BarcodeGeneratorPNG;

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
            'item_ids' => [$inventory->id],
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
        $generatedIds = [];

        foreach ($request->item_ids as $itemId) {
            $item = Inventory::find($itemId);
            
            if ($item && !$item->barcode) {
                $barcode = $this->generateBarcode($item);
                $item->update(['barcode' => $barcode]);
                $generated++;
                $generatedIds[] = $item->id;
            } else {
                $skipped++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generated} barcode(s), skipped {$skipped} item(s) that already have barcodes",
            'generated' => $generated,
            'skipped' => $skipped,
            'item_ids' => $generatedIds,
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
        $generatedIds = [];

        foreach ($items as $item) {
            $barcode = $this->generateBarcode($item);
            $item->update(['barcode' => $barcode]);
            $generated++;
            $generatedIds[] = $item->id;
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generated} barcode(s)",
            'generated' => $generated,
            'item_ids' => $generatedIds,
        ]);
    }

    /**
     * Download PDF with barcodes for printing stickers
     */
    public function downloadPDF(Request $request)
    {
        // Increase memory limit for large PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); // 5 minutes
        
        // Support both GET and POST requests
        // For POST, get from request body, for GET from query string
        if ($request->isMethod('post')) {
            // Handle JSON input for large datasets
            if ($request->has('item_ids_json')) {
                $itemIds = json_decode($request->input('item_ids_json'), true) ?? [];
            } else {
                $itemIds = $request->input('item_ids', []);
            }
        } else {
            $itemIds = $request->input('item_ids', []);
        }
        
        // Ensure item_ids is an array
        if (!is_array($itemIds)) {
            $itemIds = [];
        }
        
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

        // Generate barcode images for each item
        $generator = new BarcodeGeneratorPNG();
        $itemsWithBarcodes = $items->map(function($item) use ($generator) {
            try {
                // Generate CODE128 barcode image - optimized size for stickers
                // Width factor 1.5 (thinner bars), height 30px for better scanning
                $barcodeImage = $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 1.5, 30);
                $item->barcode_image_base64 = 'data:image/png;base64,' . base64_encode($barcodeImage);
            } catch (\Exception $e) {
                // If barcode generation fails, set to null
                $item->barcode_image_base64 = null;
            }
            return $item;
        });

        $html = view('barcodes.pdf', ['items' => $itemsWithBarcodes])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false); // Disable remote resources to save memory
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);
        $options->set('dpi', 96); // Lower DPI to reduce memory usage
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'barcode-stickers-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Display products with barcodes
     */
    public function productsWithBarcodes(Request $request)
    {
        $query = Inventory::with(['category', 'brand'])
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '');

        // Filter by recently generated (last 24 hours, 7 days, 30 days, or all)
        if ($request->filled('recent')) {
            switch ($request->recent) {
                case 'today':
                    $query->whereDate('updated_at', today());
                    break;
                case '24h':
                    $query->where('updated_at', '>=', now()->subDay());
                    break;
                case '7d':
                    $query->where('updated_at', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->where('updated_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Order by most recently updated first
        $query->orderBy('updated_at', 'desc');

        $items = $query->paginate(50);

        // Get categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('barcodes.products', compact('items', 'categories'));
    }

    /**
     * Get recently generated barcodes
     */
    public function recentlyGenerated(Request $request)
    {
        // Increase memory limit for large PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); // 5 minutes
        
        $hours = $request->input('hours', 24); // Default to last 24 hours
        $limit = $request->input('limit', 500); // Limit items per PDF to prevent memory issues
        
        $query = Inventory::with(['category', 'brand'])
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->where('updated_at', '>=', now()->subHours($hours))
            ->orderBy('updated_at', 'desc');
        
        $totalCount = $query->count();
        
        // Limit items to prevent memory exhaustion
        $items = $query->limit($limit)->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'No recently generated barcodes found');
        }

        // Generate barcode images for each item
        $generator = new BarcodeGeneratorPNG();
        $itemsWithBarcodes = $items->map(function($item) use ($generator) {
            try {
                // Generate CODE128 barcode image - optimized size for stickers
                $barcodeImage = $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 1.5, 30);
                $item->barcode_image_base64 = 'data:image/png;base64,' . base64_encode($barcodeImage);
            } catch (\Exception $e) {
                $item->barcode_image_base64 = null;
            }
            return $item;
        });

        $html = view('barcodes.pdf', ['items' => $itemsWithBarcodes])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false); // Disable remote resources to save memory
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);
        $options->set('dpi', 96); // Lower DPI to reduce memory usage
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'recently-generated-barcodes-' . now()->format('Y-m-d-H-i-s');
        if ($totalCount > $limit) {
            $filename .= '-limited-' . $limit . '-of-' . $totalCount;
        }

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, $filename . '.pdf');
    }

    /**
     * Generate barcode image
     */
    public function barcodeImage($barcode)
    {
        try {
            $generator = new BarcodeGeneratorPNG();
            // Using CODE128 for standard barcode format
            // Width factor 1.5 (thinner bars for better scanning), height 50px
            $barcodeImage = $generator->getBarcode($barcode, $generator::TYPE_CODE_128, 1.5, 50);
            
            return response($barcodeImage)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline; filename="barcode-' . $barcode . '.png"');
        } catch (\Exception $e) {
            // Return a simple error image if barcode generation fails
            $image = imagecreate(200, 50);
            $bg = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            imagestring($image, 5, 10, 15, 'Barcode Error', $textColor);
            
            ob_start();
            imagepng($image);
            $imageData = ob_get_contents();
            ob_end_clean();
            imagedestroy($image);
            
            return response($imageData)
                ->header('Content-Type', 'image/png');
        }
    }

    /**
     * Generate a unique 8-digit barcode for an item
     */
    private function generateBarcode(Inventory $inventory): string
    {
        // Generate 8-digit numeric barcode
        // Start with inventory ID modulo to fit in 8 digits, then pad
        $baseNumber = ($inventory->id * 1000 + crc32($inventory->part_number ?: $inventory->name)) % 100000000;
        
        // Ensure it's exactly 8 digits
        $barcode = str_pad((string)$baseNumber, 8, '0', STR_PAD_LEFT);
        
        // Ensure barcode is unique
        $counter = 1;
        $originalBarcode = $barcode;
        
        while (Inventory::where('barcode', $barcode)->where('id', '!=', $inventory->id)->exists()) {
            // If collision, try incrementing the last digits
            $newNumber = ($baseNumber + $counter) % 100000000;
            $barcode = str_pad((string)$newNumber, 8, '0', STR_PAD_LEFT);
            $counter++;
            
            // Safety check to prevent infinite loop
            if ($counter > 1000) {
                // Fallback: use timestamp-based approach
                $timestamp = time() % 100000000;
                $barcode = str_pad((string)$timestamp, 8, '0', STR_PAD_LEFT);
                // Check one more time
                if (Inventory::where('barcode', $barcode)->where('id', '!=', $inventory->id)->exists()) {
                    $barcode = str_pad((string)(($timestamp + $inventory->id) % 100000000), 8, '0', STR_PAD_LEFT);
                }
                break;
            }
        }

        return $barcode;
    }
}
