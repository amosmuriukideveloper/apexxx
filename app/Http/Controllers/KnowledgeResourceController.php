<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeResourceController extends Controller
{
    public function index(Request $request)
    {
        // Get all approved courses and resources
        $courses = Course::where('status', 'approved')
            ->where('is_active', true)
            ->with(['creator', 'enrollments'])
            ->latest()
            ->get();

        $studyResources = StudyResource::where('status', 'approved')
            ->where('is_active', true)
            ->with(['creator'])
            ->latest()
            ->get();

        // Resource types for filter
        $types = [
            'course' => 'Courses',
            'study_guide' => 'Study Guides',
            'notes' => 'Study Notes',
            'sample_paper' => 'Sample Papers',
            'video' => 'Video Tutorials',
        ];

        return view('knowledge-resources.index', compact('courses', 'studyResources', 'types'));
    }

    public function show($id, $type = 'course')
    {
        if ($type === 'course') {
            $resource = Course::where('id', $id)
                ->where('status', 'approved')
                ->with(['creator', 'modules', 'reviews'])
                ->firstOrFail();
        } else {
            $resource = StudyResource::where('id', $id)
                ->where('status', 'approved')
                ->with(['creator'])
                ->firstOrFail();
        }

        // Check if user owns this resource
        $userOwns = false;
        if (Auth::check()) {
            if ($type === 'course') {
                $userOwns = $resource->enrollments()
                    ->where('student_id', Auth::id())
                    ->exists();
            } else {
                $userOwns = $resource->purchases()
                    ->where('student_id', Auth::id())
                    ->exists();
            }
        }

        return view('knowledge-resources.show', compact('resource', 'type', 'userOwns'));
    }

    public function checkout($id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('intended', route('knowledge-resources.checkout', $id))
                ->with('message', 'Please login to purchase this resource.');
        }

        $type = $request->query('type', 'course');

        if ($type === 'course') {
            $resource = Course::findOrFail($id);
            
            // Check if already enrolled
            if ($resource->enrollments()->where('student_id', Auth::id())->exists()) {
                return redirect()->route('student.dashboard.my-courses')
                    ->with('info', 'You are already enrolled in this course.');
            }
        } else {
            $resource = StudyResource::findOrFail($id);
            
            // Check if already purchased
            if ($resource->purchases()->where('student_id', Auth::id())->exists()) {
                return redirect()->route('student.dashboard.my-resources')
                    ->with('info', 'You already own this resource.');
            }
        }

        return view('knowledge-resources.checkout', compact('resource', 'type'));
    }

    public function purchase(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $type = $request->input('type', 'course');
        
        // This will be integrated with the payment system
        // For now, create a placeholder
        
        return redirect()->route('knowledge-resources.payment', [
            'id' => $id,
            'type' => $type,
        ]);
    }
}
