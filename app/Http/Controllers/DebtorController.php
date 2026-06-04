<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtorController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function index(Request $request)
    {
        $customers = Customer::query()
            ->with(['sales' => fn ($q) => $q->with('payments')->outstanding()])
            ->orderBy('name')
            ->get()
            ->map(function (Customer $customer) {
                $customer->computed_balance = $customer->sales->sum(fn (Sale $sale) => $sale->balanceDue());
                $customer->open_invoices = $customer->sales->count();

                return $customer;
            });

        if ($request->filled('filter') && $request->filter === 'with_balance') {
            $customers = $customers->filter(fn ($c) => $c->computed_balance > 0.01);
        }

        $totalOutstanding = round($customers->sum('computed_balance'), 2);
        $debtorCount = $customers->filter(fn ($c) => $c->computed_balance > 0.01)->count();

        $recentCreditSales = Sale::with(['customer', 'payments'])
            ->credit()
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        return view('debtors.index', compact(
            'customers',
            'totalOutstanding',
            'debtorCount',
            'recentCreditSales'
        ));
    }

    public function show(Customer $customer)
    {
        $customer->loadCount('sales');

        $openSales = $customer->openCreditSales()->get();
        $paidCreditSales = $customer->sales()
            ->credit()
            ->whereIn('payment_status', ['paid', 'completed'])
            ->with(['payments', 'saleItems.part'])
            ->orderByDesc('date')
            ->limit(20)
            ->get();

        $balance = $customer->outstandingBalance();

        return view('debtors.show', compact('customer', 'openSales', 'paidCreditSales', 'balance'));
    }

    public function recordPayment(Request $request, Sale $sale)
    {
        if (! $sale->is_credit && $sale->isSettled()) {
            return back()->with('error', 'This sale is already fully paid.');
        }

        if ($sale->balanceDue() < 0.01) {
            return back()->with('error', 'No balance remaining on this invoice.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:Cash,M-Pesa',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->saleService->recordPayment($sale, $validated, Auth::id());

            $sale->refresh();
            $message = $sale->isSettled()
                ? "Payment recorded. Invoice {$sale->invoice_number} is now fully paid."
                : 'Payment recorded. Balance remaining: KES ' . number_format($sale->balanceDue(), 2);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'balance_due' => $sale->balanceDue(),
                    'payment_status' => $sale->payment_status,
                ]);
            }

            $redirect = $sale->customer_id
                ? route('debtors.show', $sale->customer_id)
                : route('sales.show', $sale);

            return redirect($redirect)->with('success', $message);
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['amount' => $e->getMessage()])->withInput();
        }
    }
}
