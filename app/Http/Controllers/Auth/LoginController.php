<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string|size:4',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !$user->verifyPin($request->pin)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'username' => ['Your account is inactive. Please contact administrator.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return route('dashboard');
        } else if ($user->isCashier()) {
            return route('pos.index');
        }

        return route('dashboard');
    }
}
