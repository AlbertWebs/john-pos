<?php

namespace App\Http\Controllers;

use App\Models\NextOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NextOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = NextOrder::with('requester')->orderByDesc('created_at');

        if ($request->filled('status') && in_array($request->status, NextOrder::statuses(), true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('item_name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_contact', 'like', "%{$search}%");
            });
        }

        $nextOrders = $query->paginate(20)->withQueryString();

        return view('next-orders.index', [
            'nextOrders' => $nextOrders,
            'statuses' => NextOrder::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'part_number' => 'nullable|string|max:255',
            'requested_quantity' => 'nullable|integer|min:1|max:1000',
            'customer_name' => 'nullable|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $nextOrder = NextOrder::create([
            'item_name' => $validated['item_name'],
            'part_number' => $validated['part_number'] ?? null,
            'requested_quantity' => $validated['requested_quantity'] ?? 1,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_contact' => $validated['customer_contact'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => NextOrder::STATUS_PENDING,
            'requested_by' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Next order recorded successfully.',
                'data' => $nextOrder,
            ]);
        }

        return redirect()->route('next-orders.index')
            ->with('success', 'Next order recorded successfully.');
    }

    public function updateStatus(Request $request, NextOrder $nextOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', NextOrder::statuses()),
        ]);

        DB::transaction(function () use ($nextOrder, $validated) {
            $status = $validated['status'];
            $timestamps = [
                'ordered_at' => $nextOrder->ordered_at,
                'fulfilled_at' => $nextOrder->fulfilled_at,
            ];

            if ($status === NextOrder::STATUS_ORDERED && !$nextOrder->ordered_at) {
                $timestamps['ordered_at'] = now();
            }

            if ($status === NextOrder::STATUS_COMPLETED && !$nextOrder->fulfilled_at) {
                $timestamps['fulfilled_at'] = now();
            }

            if (in_array($status, [NextOrder::STATUS_PENDING, NextOrder::STATUS_CANCELLED], true)) {
                $timestamps['ordered_at'] = null;
                $timestamps['fulfilled_at'] = null;
            }

            $nextOrder->update(array_merge(['status' => $status], $timestamps));
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Next order status updated successfully.',
                'data' => $nextOrder->fresh('requester'),
            ]);
        }

        return back()->with('success', 'Next order status updated successfully.');
    }

    public function markPurchased(Request $request)
    {
        $validated = $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:next_orders,id',
        ]);

        DB::transaction(function () use ($validated) {
            $orders = NextOrder::whereIn('id', $validated['selected_ids'])
                ->lockForUpdate()
                ->get();

            foreach ($orders as $order) {
                $timestamps = [];
                if (!$order->ordered_at) {
                    $timestamps['ordered_at'] = now();
                }
                $timestamps['fulfilled_at'] = now();

                $order->update(array_merge([
                    'status' => NextOrder::STATUS_COMPLETED,
                ], $timestamps));
            }
        });

        $updatedOrders = NextOrder::whereIn('id', $validated['selected_ids'])
            ->with('requester')
            ->get();

        $message = sprintf('%d next order(s) marked as purchased.', $updatedOrders->count());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $updatedOrders,
            ]);
        }

        return back()->with('success', $message);
    }
}

