<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authService->login(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records or account is inactive.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath($user));
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated()
        );

        Auth::login($user);

        return redirect()->intended($this->redirectPath($user));
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectPath($user)
    {
        if ($user->hasRole('admin')) {
            return route('dashboard.admin');
        } elseif ($user->hasRole('teacher')) {
            return route('dashboard.teacher');
        } elseif ($user->hasRole('student')) {
            return route('dashboard.student');
        } elseif ($user->hasRole('guardian')) {
            return route('dashboard.guardian');
        }

        return route('dashboard.student'); // Fallback
    }
}
