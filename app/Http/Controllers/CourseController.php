<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_courses')->only(['index', 'show']);
        $this->middleware('permission:create_courses')->only(['create', 'store']);
        $this->middleware('permission:edit_courses')->only(['edit', 'update']);
        $this->middleware('permission:approve_courses')->only(['approve']);
        $this->middleware('permission:enroll_courses')->only(['enroll']);
    }

    /**
     * Display courses based on user role
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            // Students see available and enrolled courses
            return view('courses.index', [
                'courses' => collect(), // Course::published()
                'enrolledCourses' => collect(), // $user->enrolledCourses()
                'canEnroll' => true,
                'canCreate' => false
            ]);
        }
        
        if ($user->isContentCreator()) {
            // Content creators see their own courses
            return view('courses.index', [
                'courses' => collect(), // $user->createdCourses()
                'canEnroll' => false,
                'canCreate' => true
            ]);
        }
        
        if ($user->isAnyAdmin()) {
            // Admins see all courses for approval
            return view('courses.index', [
                'courses' => collect(), // Course::all()
                'pendingCourses' => collect(), // Course::pending()
                'canEnroll' => false,
                'canCreate' => false,
                'canApprove' => true
            ]);
        }
        
        return view('courses.index', ['courses' => collect()]);
    }

    /**
     * Create new course (Content Creator only)
     */
    public function create()
    {
        $this->authorize('create_courses');
        
        return view('courses.create');
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $this->authorize('create_courses');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
        ]);
        
        // Course creation logic here
        return redirect()->route('courses.index')
            ->with('success', 'Course created and submitted for approval!');
    }

    /**
     * Enroll in course (Student only)
     */
    public function enroll(Request $request, $courseId)
    {
        $this->authorize('enroll_courses');
        
        // Enrollment logic here
        return redirect()->back()
            ->with('success', 'Successfully enrolled in course!');
    }

    /**
     * Approve course (Admin only)
     */
    public function approve(Request $request, $courseId)
    {
        $this->authorize('approve_courses');
        
        // Approval logic here
        return redirect()->back()
            ->with('success', 'Course approved and published!');
    }
}
