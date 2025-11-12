<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyPointsController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort by points
        $sortBy = $request->get('sort_by', 'points');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'points') {
            $query->orderBy('loyalty_points', $sortOrder);
        } else {
            $query->orderBy('name', $sortOrder);
        }

        $customers = $query->paginate(20);

        return view('loyalty-points.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load('sales');
        
        // Calculate statistics
        $stats = [
            'total_points' => $customer->loyalty_points,
            'total_spent' => $customer->getTotalPurchases(),
            'total_transactions' => $customer->getTotalTransactions(),
            'points_earned_from_sales' => floor($customer->getTotalPurchases() / 100),
        ];

        // Get points history (from sales)
        $pointsHistory = $customer->sales()
            ->whereNotNull('customer_id')
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get()
            ->map(function($sale) {
                $points = floor($sale->total_amount / 100);
                return [
                    'date' => $sale->date,
                    'transaction' => $sale->invoice_number,
                    'amount' => $sale->total_amount,
                    'points' => $points,
                    'type' => 'earned',
                ];
            });

        return view('loyalty-points.show', compact('customer', 'stats', 'pointsHistory'));
    }

    public function redeem(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:1|max:' . $customer->loyalty_points,
            'discount_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Conversion rate: 100 points = 100 KES discount (1 point = 1 KES)
        $conversionRate = 1;

        // Deduct points
        $customer->decrement('loyalty_points', $validated['points']);
        $customer->refresh();

        $message = "Successfully redeemed {$validated['points']} points for KES {$validated['discount_amount']} discount.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'remaining_points' => $customer->loyalty_points,
                'discount_amount' => $validated['discount_amount'],
            ]);
        }

        return redirect()->route('loyalty-points.show', $customer)
            ->with('success', $message);
    }

    public function adjust(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'reason' => 'required|string|max:500',
            'type' => 'required|in:add,deduct',
        ]);

        DB::beginTransaction();
        try {
            if ($validated['type'] === 'add') {
                $customer->increment('loyalty_points', abs($validated['points']));
                $message = "Added {$validated['points']} points to customer account.";
            } else {
                if ($customer->loyalty_points < abs($validated['points'])) {
                    throw new \Exception("Cannot deduct more points than customer has.");
                }
                $customer->decrement('loyalty_points', abs($validated['points']));
                $message = "Deducted {$validated['points']} points from customer account.";
            }

            // Log the adjustment (you could create a loyalty_points_transactions table)
            
            DB::commit();

            $customer->refresh();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'remaining_points' => $customer->loyalty_points,
                ]);
            }

            return redirect()->route('loyalty-points.show', $customer)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function calculateDiscount($points)
    {
        // Conversion rate: 1 point = 1 KES
        $conversionRate = 1;
        return $points * $conversionRate;
    }
}
