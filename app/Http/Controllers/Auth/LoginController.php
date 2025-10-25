<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return '/platform';
        }
        
        if ($user->hasRole('student')) {
            return '/student';
        }
        
        if ($user->hasRole('expert')) {
            return '/expert';
        }
        
        if ($user->hasRole('tutor')) {
            return '/tutor';
        }
        
        if ($user->hasRole('content_creator')) {
            return '/creator';
        }
        
        return '/dashboard';
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended($this->redirectTo());
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been logged out of the application.
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
