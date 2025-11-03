<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();
        $inventory = Inventory::where('status', 'active')->get();

        if ($inventory->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No inventory or users found. Please run InventorySeeder and UserSeeder first.');
            return;
        }

        // Generate sales for the last 60 days
        for ($i = 0; $i < 50; $i++) {
            $date = now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $customer = rand(0, 1) ? $customers->random() : null;
            $user = $users->random();
            
            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($date);
            
            // Select 1-5 random inventory items
            $items = $inventory->random(rand(1, 5));
            $subtotal = 0;
            $saleItems = [];
            
            foreach ($items as $item) {
                $quantity = rand(1, min(5, $item->stock_quantity));
                $price = $item->selling_price;
                $itemSubtotal = $quantity * $price;
                $subtotal += $itemSubtotal;
                
                $saleItems[] = [
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                ];
            }
            
            $tax = $subtotal * 0.16; // 16% VAT
            $discount = rand(0, 1) ? rand(500, 2000) : 0;
            $totalAmount = $subtotal + $tax - $discount;
            
            $paymentMethod = rand(0, 1) ? 'Cash' : 'M-Pesa';
            
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer?->id,
                'user_id' => $user->id,
                'date' => $date,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'payment_status' => 'completed',
            ]);
            
            // Create sale items
            foreach ($saleItems as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'part_id' => $itemData['item']->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal'],
                ]);
                
                // Update inventory stock (don't decrement in seeders for demo purposes)
                // $itemData['item']->decrement('stock_quantity', $itemData['quantity']);
            }
            
            // Create payment
            Payment::create([
                'sale_id' => $sale->id,
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'transaction_reference' => $paymentMethod === 'M-Pesa' ? 'MP' . str_pad(rand(1000000, 9999999), 10, '0', STR_PAD_LEFT) : null,
                'payment_date' => $date,
            ]);
            
            // Update customer loyalty points
            if ($customer) {
                $points = floor($totalAmount / 100);
                if ($points > 0) {
                    $customer->increment('loyalty_points', $points);
                }
            }
        }
    }
    
    private function generateInvoiceNumber($date)
    {
        $year = $date->format('Y');
        $month = $date->format('m');
        $lastNumber = Sale::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();
        
        return sprintf('INV-%s%s-%04d', $year, $month, $lastNumber + 1);
    }
}
