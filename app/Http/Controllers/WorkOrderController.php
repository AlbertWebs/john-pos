<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\Customer;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with(['customer', 'vehicleMake', 'vehicleModel', 'assignedTo', 'createdBy']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('work_order_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_registration', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $workOrders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $users = \App\Models\User::orderBy('name')->get();

        return view('work-orders.index', compact('workOrders', 'users'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        $vehicleModels = VehicleModel::with('vehicleMake')->orderBy('model_name')->get();
        $inventory = Inventory::where('status', 'active')->orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();

        return view('work-orders.create', compact('customers', 'vehicleMakes', 'vehicleModels', 'inventory', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'vehicle_registration' => 'nullable|string|max:255',
            'vehicle_year' => 'nullable|string|max:10',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.part_id' => 'nullable|exists:inventory,id',
            'items.*.item_description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.type' => 'required|in:part,labor,other',
        ]);

        DB::beginTransaction();
        try {
            // Generate work order number
            $workOrderNumber = $this->generateWorkOrderNumber();

            // Create work order
            $workOrder = WorkOrder::create([
                'work_order_number' => $workOrderNumber,
                'customer_id' => $validated['customer_id'] ?? null,
                'vehicle_make_id' => $validated['vehicle_make_id'] ?? null,
                'vehicle_model_id' => $validated['vehicle_model_id'] ?? null,
                'vehicle_registration' => $validated['vehicle_registration'] ?? null,
                'vehicle_year' => $validated['vehicle_year'] ?? null,
                'description' => $validated['description'],
                'status' => $validated['status'],
                'estimated_cost' => $validated['estimated_cost'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'created_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create work order items
            foreach ($validated['items'] as $item) {
                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'part_id' => $item['part_id'] ?? null,
                    'item_description' => $item['item_description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'type' => $item['type'],
                ]);
            }

            DB::commit();

            return redirect()->route('work-orders.show', $workOrder)
                ->with('success', 'Work order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['customer', 'vehicleMake', 'vehicleModel', 'assignedTo', 'createdBy', 'items.part']);
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('work-orders.edit', compact('workOrder', 'users'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'vehicle_registration' => 'nullable|string|max:255',
            'vehicle_year' => 'nullable|string|max:10',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $workOrder->update($validated);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order updated successfully.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', 'Work order deleted successfully.');
    }

    private function generateWorkOrderNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastOrder = WorkOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->work_order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('WO-%s%s-%04d', $year, $month, $newNumber);
    }
}
