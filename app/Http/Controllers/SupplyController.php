<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json(Supply::active()->orderBy('name')->get());
        }

        $supplies = Supply::orderBy('name')->paginate(15);

        return view('supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('supplies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:supplies,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
        ]);

        Supply::create($validated);

        if ($request->filled('redirect')) {
            return redirect()->to($request->redirect)
                ->with('success', 'Supply created successfully.');
        }

        return redirect()->route('supplies.index')
            ->with('success', 'Supply created successfully.');
    }

    public function show(Supply $supply)
    {
        $supply->loadCount('inventory');

        return view('supplies.show', compact('supply'));
    }

    public function edit(Supply $supply)
    {
        return view('supplies.edit', compact('supply'));
    }

    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:supplies,name,' . $supply->id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
        ]);

        $supply->update($validated);

        return redirect()->route('supplies.index')
            ->with('success', 'Supply updated successfully.');
    }

    public function destroy(Supply $supply)
    {
        if ($supply->inventory()->count() > 0) {
            return redirect()->route('supplies.index')
                ->with('error', 'Cannot delete a supply that is linked to inventory items.');
        }

        $supply->delete();

        return redirect()->route('supplies.index')
            ->with('success', 'Supply deleted successfully.');
    }
}
