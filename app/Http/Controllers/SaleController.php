<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\InventoryMovement;
use App\Services\ETimsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

class SaleController extends Controller
{
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
            'payment_method' => 'required|in:Cash,M-Pesa',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'pending_payment_id' => 'nullable|exists:pending_payments,id',
            'generate_etims_receipt' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create sale
            // If pending payment is being allocated, set payment status to 'pending' (will be updated during allocation)
            // Otherwise, set to 'completed' for immediate payment
            $paymentStatus = $request->filled('pending_payment_id') ? 'pending' : 'completed';
            
            $generateEtimsReceipt = $request->boolean('generate_etims_receipt', false);
            
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => Auth::id(),
                'date' => now(),
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_status' => $paymentStatus,
                'generate_etims_receipt' => $generateEtimsReceipt,
            ]);

            // Create sale items and update inventory
            foreach ($validated['items'] as $item) {
                $inventory = Inventory::findOrFail($item['part_id']);
                
                // Check stock availability
                if ($inventory->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$inventory->name}");
                }

                // Check minimum price
                if ($item['price'] < $inventory->min_price) {
                    throw new \Exception("Price below minimum for {$inventory->name}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'part_id' => $item['part_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update inventory stock
                $inventory->decrement('stock_quantity', $item['quantity']);

                // Create inventory movement
                InventoryMovement::create([
                    'part_id' => $item['part_id'],
                    'change_quantity' => -$item['quantity'],
                    'movement_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => Auth::id(),
                    'timestamp' => now(),
                ]);
            }

            // Create payment record (skip if pending payment is being allocated - it will be created during allocation)
            if (!$request->filled('pending_payment_id')) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $validated['total_amount'],
                    'transaction_reference' => $request->transaction_reference ?? null,
                    'payment_date' => now(),
                ]);
            }

            // Update customer loyalty points (if customer exists)
            if ($sale->customer_id) {
                // Award 1 point per 100 KES spent
                $points = floor($validated['total_amount'] / 100);
                if ($points > 0) {
                    $sale->customer->increment('loyalty_points', $points);
                }
            }

            DB::commit();

            // Send to eTIMS if requested
            $etimsMessage = null;
            if ($generateEtimsReceipt && $validated['tax'] > 0) {
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

        } catch (\Exception $e) {
            DB::rollBack();
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

    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastSale = Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('INV-%s%s-%04d', $year, $month, $newNumber);
    }
}
