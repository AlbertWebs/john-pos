<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingPayment;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendingPaymentController extends Controller
{
    /**
     * Display a listing of pending payments
     */
    public function index(Request $request)
    {
        $query = PendingPayment::pending()
            ->orderBy('transaction_date', 'desc');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('account_reference', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $pendingPayments = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json($pendingPayments);
        }

        return view('pending-payments.index', compact('pendingPayments'));
    }

    /**
     * Get pending payments for AJAX
     */
    public function getPending(Request $request)
    {
        $pendingPayments = PendingPayment::pending()
            ->orderBy('transaction_date', 'desc')
            ->limit(50)
            ->get();

        return response()->json($pendingPayments);
    }

    /**
     * Search for sales to allocate payment
     */
    public function searchSales(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|min:2',
        ]);

        $sales = Sale::with(['customer', 'user'])
            ->where(function ($query) use ($validated) {
                $query->where('invoice_number', 'like', "%{$validated['search']}%")
                    ->orWhereHas('customer', function ($q) use ($validated) {
                        $q->where('name', 'like', "%{$validated['search']}%")
                            ->orWhere('phone', 'like', "%{$validated['search']}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'customer_name' => $sale->customer?->name ?? 'Walk-in Customer',
                    'customer_phone' => $sale->customer?->phone ?? null,
                    'total_amount' => $sale->total_amount,
                    'payment_status' => $sale->payment_status,
                    'date' => $sale->date->format('Y-m-d'),
                    'user_name' => $sale->user->name ?? null,
                ];
            });

        return response()->json($sales);
    }

    /**
     * Allocate payment to a sale
     */
    public function allocate(Request $request, PendingPayment $pendingPayment)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::findOrFail($validated['sale_id']);

            // Check if payment amount matches sale amount (allow some flexibility)
            $difference = abs($pendingPayment->amount - $sale->total_amount);
            $tolerance = 0.01; // Allow 1 cent difference

            if ($difference > $tolerance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount does not match sale amount. Please verify.',
                    'payment_amount' => $pendingPayment->amount,
                    'sale_amount' => $sale->total_amount,
                    'difference' => $difference,
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'sale_id' => $sale->id,
                'payment_method' => 'M-Pesa',
                'amount' => $pendingPayment->amount,
                'transaction_reference' => $pendingPayment->transaction_reference,
                'payment_date' => $pendingPayment->transaction_date,
            ]);

            // Update pending payment status
            $pendingPayment->update([
                'sale_id' => $sale->id,
                'status' => 'allocated',
            ]);

            // Update sale payment status
            $totalPaid = $sale->payments()->sum('amount');
            
            if ($totalPaid >= $sale->total_amount) {
                $sale->payment_status = 'paid';
            } elseif ($totalPaid > 0) {
                $sale->payment_status = 'partial';
            }
            
            $sale->save();

            // Update customer loyalty points if applicable
            if ($sale->customer_id && $pendingPayment->amount > 0) {
                $points = floor($pendingPayment->amount / 100);
                if ($points > 0) {
                    $sale->customer->increment('loyalty_points', $points);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment allocated successfully',
                'payment' => $payment,
                'sale' => $sale,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Allocation Error', [
                'error' => $e->getMessage(),
                'pending_payment_id' => $pendingPayment->id,
                'sale_id' => $validated['sale_id'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to allocate payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel/delete a pending payment
     */
    public function cancel(Request $request, PendingPayment $pendingPayment)
    {
        try {
            $pendingPayment->update([
                'status' => 'cancelled',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pending payment cancelled',
            ]);

        } catch (\Exception $e) {
            Log::error('Cancel Pending Payment Error', [
                'error' => $e->getMessage(),
                'pending_payment_id' => $pendingPayment->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show details of a pending payment
     */
    public function show(PendingPayment $pendingPayment)
    {
        $pendingPayment->load('sale.customer', 'sale.user');

        if (request()->ajax()) {
            return response()->json($pendingPayment);
        }

        return view('pending-payments.show', compact('pendingPayment'));
    }
}
