<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\ETimsService;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function index()
    {
        $sales = Sale::with(['customer', 'user', 'saleItems.part', 'payments', 'returns'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('sales.index', compact('sales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.part_id' => 'required|exists:inventory,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,M-Pesa,Credit',
            'due_date' => 'nullable|date|after_or_equal:today',
            'credit_notes' => 'nullable|string|max:2000',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'pending_payment_id' => 'nullable|exists:pending_payments,id',
            'generate_etims_receipt' => 'nullable|boolean',
        ]);

        try {
            $generateEtimsReceipt = $request->boolean('generate_etims_receipt', false);
            $validated['transaction_reference'] = $request->transaction_reference ?? null;

            $sale = $this->saleService->createSale(
                $validated,
                Auth::id(),
                $request->filled('pending_payment_id') ? (int) $request->pending_payment_id : null
            );

            // Send to eTIMS if requested
            $etimsMessage = null;
            if ($generateEtimsReceipt && ($validated['tax'] ?? 0) > 0) {
                try {
                    $etimsService = new ETimsService();
                    $etimsResult = $etimsService->sendInvoice($sale);
                    
                    if ($etimsResult['success']) {
                        $etimsMessage = 'Your eTIMS request has been sent. Awaiting confirmation from KRA…';
                    } else {
                        $etimsMessage = 'eTIMS request failed: ' . $etimsResult['message'];
                        Log::warning('eTIMS send failed', [
                            'sale_id' => $sale->id,
                            'error' => $etimsResult['message'],
                        ]);
                    }
                } catch (\Exception $e) {
                    $etimsMessage = 'Error sending to eTIMS: ' . $e->getMessage();
                    Log::error('eTIMS exception', [
                        'sale_id' => $sale->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'redirect_url' => route('sales.show', $sale),
                'etims_message' => $etimsMessage,
            ]);

        } catch (\InvalidArgumentException|\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(Sale $sale, Request $request)
    {
        $sale->load(['customer', 'user', 'saleItems.part', 'payments']);
        
        // Get company settings
        $settings = \Illuminate\Support\Facades\DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();
        
        // PDF Export
        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportReceiptPDF($sale);
        }
        
        return view('sales.show', compact('sale', 'settings'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleItems.part', 'payments']);
        
        // Get company settings
        $settings = \Illuminate\Support\Facades\DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();
        
        return view('sales.print', compact('sale', 'settings'));
    }

    private function exportReceiptPDF(Sale $sale)
    {
        // Get company settings
        $settings = \Illuminate\Support\Facades\DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();
        
        $html = view('sales.pdf', compact('sale', 'settings'))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // Set paper size for 80mm thermal printer (72mm printable width)
        // 72mm = 283.464 points (width)
        // Height will be calculated automatically based on content
        $dompdf->setPaper([0, 0, 283.464, 10000], 'portrait');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'receipt-' . $sale->invoice_number . '.pdf');
    }

}
