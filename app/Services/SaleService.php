<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function createSale(array $validated, int $userId, ?int $pendingPaymentId = null): Sale
    {
        $isCredit = ($validated['payment_method'] ?? '') === 'Credit';

        if ($isCredit && empty($validated['customer_id'])) {
            throw new \InvalidArgumentException('A customer is required for credit (debtor) sales.');
        }

        return DB::transaction(function () use ($validated, $userId, $pendingPaymentId, $isCredit) {
            $invoiceNumber = $this->generateInvoiceNumber();

            if ($pendingPaymentId) {
                $paymentStatus = 'pending';
            } elseif ($isCredit) {
                $paymentStatus = 'pending';
            } else {
                $paymentStatus = 'completed';
            }

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => $userId,
                'date' => now(),
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_status' => $paymentStatus,
                'is_credit' => $isCredit,
                'due_date' => ! empty($validated['due_date']) ? $validated['due_date'] : null,
                'credit_notes' => $validated['credit_notes'] ?? null,
                'generate_etims_receipt' => (bool) ($validated['generate_etims_receipt'] ?? false),
            ]);

            foreach ($validated['items'] as $item) {
                $inventory = Inventory::findOrFail($item['part_id']);

                if ($inventory->stock_quantity < $item['quantity']) {
                    throw new \InvalidArgumentException("Insufficient stock for {$inventory->name}");
                }

                if ($item['price'] < $inventory->min_price) {
                    throw new \InvalidArgumentException("Price below minimum for {$inventory->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'part_id' => $item['part_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                $inventory->decrement('stock_quantity', $item['quantity']);

                InventoryMovement::create([
                    'part_id' => $item['part_id'],
                    'change_quantity' => -$item['quantity'],
                    'movement_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => $userId,
                    'timestamp' => now(),
                ]);
            }

            if (! $pendingPaymentId && ! $isCredit) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $validated['total_amount'],
                    'transaction_reference' => $validated['transaction_reference'] ?? null,
                    'payment_date' => now(),
                ]);
            }

            if (! $isCredit && $sale->customer_id) {
                $this->awardLoyaltyPoints($sale->customer, (float) $validated['total_amount']);
            }

            return $sale->fresh(['customer', 'saleItems.part', 'payments']);
        });
    }

    public function recordPayment(Sale $sale, array $data, int $userId): Payment
    {
        $amount = round((float) $data['amount'], 2);
        $balance = $sale->balanceDue();

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than zero.');
        }

        if ($amount > $balance + 0.01) {
            throw new \InvalidArgumentException(
                'Payment amount (KES ' . number_format($amount, 2) . ') exceeds balance due (KES ' . number_format($balance, 2) . ').'
            );
        }

        return DB::transaction(function () use ($sale, $data, $amount) {
            $payment = Payment::create([
                'sale_id' => $sale->id,
                'payment_method' => $data['payment_method'],
                'amount' => $amount,
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'payment_date' => ! empty($data['payment_date'])
                    ? Carbon::parse($data['payment_date'])
                    : now(),
            ]);

            $sale->refresh();
            $wasSettled = $sale->isSettled();
            $sale->syncPaymentStatus();
            $sale->refresh();

            if (! $wasSettled && $sale->isSettled() && $sale->customer_id) {
                $this->awardLoyaltyPoints($sale->customer, (float) $sale->total_amount);
            }

            return $payment;
        });
    }

    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastSale = Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastSale ? ((int) substr($lastSale->invoice_number, -4)) + 1 : 1;

        return sprintf('INV-%s%s-%04d', $year, $month, $newNumber);
    }

    protected function awardLoyaltyPoints($customer, float $amount): void
    {
        $points = (int) floor($amount / 100);
        if ($points > 0) {
            $customer->increment('loyalty_points', $points);
        }
    }
}
