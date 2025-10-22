<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to appropriate panel based on user role
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return redirect('/platform');
        }
        
        if ($user->hasRole('student')) {
            return redirect('/student');
        }
        
        if ($user->hasRole('expert')) {
            return redirect('/expert');
        }
        
        if ($user->hasRole('tutor')) {
            return redirect('/tutor');
        }
        
        if ($user->hasRole('content_creator')) {
            return redirect('/creator');
        }
        
        // Fallback dashboard for users without specific roles
        return view('dashboard', compact('user'));
    }
}
