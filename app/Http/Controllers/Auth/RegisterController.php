<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
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
        $this->middleware('guest');
    }

    /**
     * Show the general registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Show the student registration form.
     */
    public function showStudentRegistration()
    {
        return view('auth.register-student');
    }

    /**
     * Show the expert registration form.
     */
    public function showExpertRegistration()
    {
        return view('auth.register-expert');
    }

    /**
     * Show the tutor registration form.
     */
    public function showTutorRegistration()
    {
        return view('auth.register-tutor');
    }

    /**
     * Show the content creator registration form.
     */
    public function showCreatorRegistration()
    {
        return view('auth.register-creator');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Register a student.
     */
    public function registerStudent(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        $user->assignRole('student');

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Register an expert.
     */
    public function registerExpert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'expertise_areas' => ['required', 'array'],
            'qualifications' => ['required', 'string'],
            'experience_years' => ['required', 'integer', 'min:0'],
        ]);

        $validator->validate();

        event(new Registered($user = $this->create($request->all())));
        $user->assignRole('expert');

        // Store additional expert data (you might want to create an Expert model)
        // Expert::create([
        //     'user_id' => $user->id,
        //     'expertise_areas' => $request->expertise_areas,
        //     'qualifications' => $request->qualifications,
        //     'experience_years' => $request->experience_years,
        // ]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Register a tutor.
     */
    public function registerTutor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'subjects' => ['required', 'array'],
            'teaching_experience' => ['required', 'integer', 'min:0'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $validator->validate();

        event(new Registered($user = $this->create($request->all())));
        $user->assignRole('tutor');

        // Store additional tutor data
        // Tutor::create([
        //     'user_id' => $user->id,
        //     'subjects' => $request->subjects,
        //     'teaching_experience' => $request->teaching_experience,
        //     'hourly_rate' => $request->hourly_rate,
        // ]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Register a content creator.
     */
    public function registerCreator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'content_types' => ['required', 'array'],
            'portfolio_url' => ['nullable', 'url'],
            'bio' => ['required', 'string', 'max:1000'],
        ]);

        $validator->validate();

        event(new Registered($user = $this->create($request->all())));
        $user->assignRole('content_creator');

        // Store additional creator data
        // ContentCreator::create([
        //     'user_id' => $user->id,
        //     'content_types' => $request->content_types,
        //     'portfolio_url' => $request->portfolio_url,
        //     'bio' => $request->bio,
        // ]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
