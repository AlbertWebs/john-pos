<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->paginate(15);
        
        // Return JSON if AJAX request (from POS)
        if ($request->expectsJson() || $request->ajax()) {
            // Get all matching customers (not paginated) for POS search
            $allCustomers = $query->orderBy('name')->limit(50)->get();
            return response()->json($allCustomers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'loyalty_points' => $customer->loyalty_points ?? 0,
                ];
            }));
        }
        
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
            ]);

            // Generate unique email if not provided
            if (empty($validated['email'])) {
                $validated['email'] = $this->generateUniqueEmail($validated['name']);
            }

            $customer = Customer::create($validated);

            // Return JSON response if AJAX request (from POS)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'phone' => $customer->phone,
                        'email' => $customer->email,
                        'loyalty_points' => $customer->loyalty_points ?? 0,
                    ],
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return JSON error response if AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Generate unique email for customer
     */
    private function generateUniqueEmail($name)
    {
        $baseEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        $email = $baseEmail . '@pos.local';
        $counter = 1;

        while (Customer::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@pos.local';
            $counter++;
        }

        return $email;
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['sales' => function($query) {
            $query->orderBy('date', 'desc')->limit(10);
        }]);
        
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->sales()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer that has sales records.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
