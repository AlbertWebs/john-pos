<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Ensure session is started
        $request->session()->regenerateToken();
        
        // Get company name from settings
        $companyName = DB::table('settings')->where('key', 'company_name')->value('value') ?? config('app.name', 'Spare Parts POS');
        
        return view('auth.login', compact('companyName'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string|size:4',
        ]);

        $user = User::where('username', $request->username)->first();

        // Check if account is locked
        if ($user && $user->isLocked()) {
            $remainingTime = $user->getRemainingLockTime();
            throw ValidationException::withMessages([
                'username' => ["Your account is locked due to too many failed login attempts. Please try again {$remainingTime}."],
            ]);
        }

        if (!$user || !$user->verifyPin($request->pin)) {
            if ($user) {
                $user->incrementLoginAttempts();
                
                $remainingAttempts = 3 - $user->login_attempts;
                if ($remainingAttempts > 0) {
                    throw ValidationException::withMessages([
                        'username' => ["The provided credentials are incorrect. {$remainingAttempts} attempt(s) remaining."],
                    ]);
                } else {
                    $remainingTime = $user->getRemainingLockTime();
                    throw ValidationException::withMessages([
                        'username' => ["Too many failed login attempts. Your account has been locked. Please try again {$remainingTime}."],
                    ]);
                }
            } else {
                throw ValidationException::withMessages([
                    'username' => ['The provided credentials are incorrect.'],
                ]);
            }
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'username' => ['Your account is inactive. Please contact administrator.'],
            ]);
        }

        // Reset login attempts on successful login
        $user->resetLoginAttempts();

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    protected function redirectTo()
    {
        // Redirect all users to POS after login
        return route('pos.index');
    }
}
