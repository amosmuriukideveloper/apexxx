<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $token,
            'redirect_url' => $this->getRedirectUrl($user),
        ]);
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,expert,tutor,content_creator',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $token,
            'redirect_url' => $this->getRedirectUrl($user),
        ], 201);
    }

    /**
     * Register user by specific type
     */
    public function registerByType(Request $request, string $type)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add type-specific validation rules
        switch ($type) {
            case 'expert':
                $rules['expertise_areas'] = 'required|array';
                $rules['qualifications'] = 'required|string';
                $rules['experience_years'] = 'required|integer|min:0';
                break;
            case 'tutor':
                $rules['subjects'] = 'required|array';
                $rules['teaching_experience'] = 'required|integer|min:0';
                $rules['hourly_rate'] = 'required|numeric|min:0';
                break;
            case 'creator':
                $rules['content_types'] = 'required|array';
                $rules['bio'] = 'required|string|max:1000';
                break;
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($type === 'creator' ? 'content_creator' : $type);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $token,
            'redirect_url' => $this->getRedirectUrl($user),
        ], 201);
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|current_password',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'user' => $user->load('roles'),
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl(User $user): string
    {
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
}
