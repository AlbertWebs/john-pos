<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountPinController extends Controller
{
    public function edit()
    {
        $this->ensureAdministrator();

        return view('account.change-pin');
    }

    public function update(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'current_pin' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
            'new_pin' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/', 'different:current_pin'],
            'new_pin_confirmation' => ['required', 'same:new_pin'],
        ], [
            'current_pin.size' => 'Your current PIN must be exactly 4 digits.',
            'current_pin.regex' => 'Your current PIN must contain only numbers (0–9).',
            'new_pin.size' => 'Your new PIN must be exactly 4 digits.',
            'new_pin.regex' => 'Your new PIN must contain only numbers (0–9).',
            'new_pin.different' => 'Your new PIN must be different from your current PIN.',
            'new_pin_confirmation.same' => 'The new PIN confirmation does not match.',
        ]);

        $user = $request->user();

        if (! $user->verifyPin($validated['current_pin'])) {
            throw ValidationException::withMessages([
                'current_pin' => ['Your current PIN is incorrect. Please try again.'],
            ]);
        }

        $user->pin = $validated['new_pin'];
        $user->resetLoginAttempts();
        $user->save();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Your PIN has been changed. Please log in again with your new PIN.');
    }

    protected function ensureAdministrator(): void
    {
        if (auth()->user()?->isCashier()) {
            abort(403, 'Only administrators can change their PIN here. Contact your manager for help.');
        }
    }
}
