<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function knowledgeHub()
    {
        $user = Auth::user();

        // Get enrolled courses
        $myCourses = Course::whereHas('enrollments', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })->with('creator')->get();

        // Get purchased resources
        $myResources = StudyResource::whereHas('purchases', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })->with('creator')->get();

        // Get available courses (not enrolled)
        $availableCourses = Course::where('status', 'approved')
            ->where('is_active', true)
            ->whereDoesntHave('enrollments', function($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->limit(6)
            ->get();

        // Get available resources (not purchased)
        $availableResources = StudyResource::where('status', 'approved')
            ->where('is_active', true)
            ->whereDoesntHave('purchases', function($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->limit(6)
            ->get();

        return view('student.dashboard.knowledge-hub', compact(
            'myCourses',
            'myResources',
            'availableCourses',
            'availableResources'
        ));
    }

    public function myCourses()
    {
        $user = Auth::user();

        $courses = Course::whereHas('enrollments', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })
        ->with(['creator', 'modules', 'enrollments' => function($query) use ($user) {
            $query->where('student_id', $user->id);
        }])
        ->get();

        return view('student.dashboard.my-courses', compact('courses'));
    }

    public function myResources()
    {
        $user = Auth::user();

        $resources = StudyResource::whereHas('purchases', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })
        ->with(['creator', 'purchases' => function($query) use ($user) {
            $query->where('student_id', $user->id);
        }])
        ->get();

        return view('student.dashboard.my-resources', compact('resources'));
    }
}
