<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Apply middleware to protect routes
        $this->middleware('auth');
        $this->middleware('permission:view_projects')->only(['index', 'show']);
        $this->middleware('permission:create_projects')->only(['create', 'store']);
        $this->middleware('permission:edit_projects')->only(['edit', 'update']);
        $this->middleware('permission:delete_projects')->only(['destroy']);
        $this->middleware('permission:assign_projects')->only(['assign']);
        $this->middleware('permission:approve_projects')->only(['approve']);
    }

    /**
     * Display a listing of projects based on user role
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            // Students see only their own projects
            return view('projects.index', [
                'projects' => $user->projects(),
                'canCreate' => true,
                'canAssign' => false
            ]);
        }
        
        if ($user->isExpert()) {
            // Experts see assigned projects
            return view('projects.index', [
                'projects' => $user->assignedProjects(),
                'canCreate' => false,
                'canAssign' => false
            ]);
        }
        
        if ($user->isAnyAdmin()) {
            // Admins see all projects
            return view('projects.index', [
                'projects' => collect(), // Project::all() when model exists
                'canCreate' => false,
                'canAssign' => true
            ]);
        }
        
        abort(403, 'Access denied');
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $this->authorize('create_projects');
        
        return view('projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $this->authorize('create_projects');
        
        // Validation and project creation logic
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:today',
        ]);
        
        // Create project logic here
        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Assign project to expert (Admin only)
     */
    public function assign(Request $request, $projectId)
    {
        $this->authorize('assign_projects');
        
        $request->validate([
            'expert_id' => 'required|exists:users,id'
        ]);
        
        // Assignment logic here
        return redirect()->back()
            ->with('success', 'Project assigned successfully!');
    }

    /**
     * Approve project deliverable (Admin only)
     */
    public function approve(Request $request, $projectId)
    {
        $this->authorize('approve_projects');
        
        // Approval logic here
        return redirect()->back()
            ->with('success', 'Project approved successfully!');
    }
}
