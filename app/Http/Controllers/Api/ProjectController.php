<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Project::with(['student', 'assignedExpert', 'subject']);

        if ($user->hasRole('student')) {
            $query->where('student_id', $user->id);
        } elseif ($user->hasRole('expert')) {
            $query->where('assigned_expert_id', $user->id);
        } elseif ($user->hasRole(['admin', 'super_admin'])) {
            // Admin can see all projects
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $projects = $query->latest()->paginate(15);

        return response()->json($projects);
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('student')) {
            return response()->json(['message' => 'Only students can create projects'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:essay,research_paper,dissertation,assignment,thesis,case_study',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'budget' => 'required|numeric|min:10',
            'pages_count' => 'required|integer|min:1',
            'academic_level' => 'required|in:high_school,undergraduate,masters,phd',
            'citation_style' => 'required|in:APA,MLA,Chicago,Harvard',
            'requirements' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'subject_id' => $request->subject_id,
            'student_id' => Auth::id(),
            'due_date' => $request->due_date,
            'budget' => $request->budget,
            'pages_count' => $request->pages_count,
            'academic_level' => $request->academic_level,
            'citation_style' => $request->citation_style,
            'requirements' => $request->requirements,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return response()->json($project->load(['subject']), 201);
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        if (!$this->canAccessProject($user, $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($project->load(['student', 'assignedExpert', 'subject', 'messages.user']));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        if (!$this->canModifyProject($user, $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rules = [];
        
        if ($user->hasRole('student') && $project->status === 'pending') {
            $rules = [
                'title' => 'string|max:255',
                'description' => 'string',
                'due_date' => 'date|after:today',
                'budget' => 'numeric|min:10',
                'requirements' => 'nullable|string',
            ];
        }

        $request->validate($rules);
        $project->update($request->only(array_keys($rules)));

        return response()->json($project->load(['subject']));
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'super_admin']) && 
            !($user->hasRole('student') && $project->student_id === $user->id && $project->status === 'pending')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }

    /**
     * Submit project work
     */
    public function submit(Request $request, Project $project)
    {
        if (!Auth::user()->hasRole('expert') || $project->assigned_expert_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'submission_notes' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // 10MB max per file
        ]);

        $project->update([
            'status' => 'submitted',
            'submission_notes' => $request->submission_notes,
            'submitted_at' => now(),
        ]);

        // Handle file uploads here
        // Store files and create ProjectSubmission records

        return response()->json(['message' => 'Project submitted successfully']);
    }

    /**
     * Request revision
     */
    public function requestRevision(Request $request, Project $project)
    {
        if (!Auth::user()->hasRole('student') || $project->student_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'revision_notes' => 'required|string',
        ]);

        $project->update([
            'status' => 'revision_requested',
            'revision_notes' => $request->revision_notes,
        ]);

        return response()->json(['message' => 'Revision requested successfully']);
    }

    /**
     * Approve project
     */
    public function approve(Project $project)
    {
        if (!Auth::user()->hasRole('student') || $project->student_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $project->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json(['message' => 'Project approved successfully']);
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request, Project $project)
    {
        if (!$this->canAccessProject(Auth::user(), $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $message = ProjectMessage::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($message->load('user'));
    }

    /**
     * Get project messages
     */
    public function getMessages(Project $project)
    {
        if (!$this->canAccessProject(Auth::user(), $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $project->messages()->with('user')->latest()->paginate(20);

        return response()->json($messages);
    }

    /**
     * Expert dashboard
     */
    public function expertDashboard()
    {
        $expertId = Auth::id();
        
        $stats = [
            'active_projects' => Project::where('assigned_expert_id', $expertId)
                ->whereIn('status', ['assigned', 'in_progress'])->count(),
            'completed_projects' => Project::where('assigned_expert_id', $expertId)
                ->where('status', 'completed')->count(),
            'pending_submissions' => Project::where('assigned_expert_id', $expertId)
                ->where('status', 'in_progress')->count(),
            'total_earnings' => 0, // Calculate from transactions
        ];

        $recentProjects = Project::where('assigned_expert_id', $expertId)
            ->with(['student', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_projects' => $recentProjects,
        ]);
    }

    /**
     * Get expert projects
     */
    public function expertProjects()
    {
        $projects = Project::where('assigned_expert_id', Auth::id())
            ->with(['student', 'subject'])
            ->latest()
            ->paginate(15);

        return response()->json($projects);
    }

    /**
     * Student dashboard
     */
    public function studentDashboard()
    {
        $studentId = Auth::id();
        
        $stats = [
            'active_projects' => Project::where('student_id', $studentId)
                ->whereIn('status', ['pending', 'assigned', 'in_progress'])->count(),
            'completed_projects' => Project::where('student_id', $studentId)
                ->where('status', 'completed')->count(),
            'pending_reviews' => Project::where('student_id', $studentId)
                ->where('status', 'submitted')->count(),
            'total_spent' => 0, // Calculate from transactions
        ];

        $recentProjects = Project::where('student_id', $studentId)
            ->with(['assignedExpert', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_projects' => $recentProjects,
        ]);
    }

    /**
     * Check if user can access project
     */
    private function canAccessProject($user, Project $project): bool
    {
        return $user->hasRole(['admin', 'super_admin']) ||
               ($user->hasRole('student') && $project->student_id === $user->id) ||
               ($user->hasRole('expert') && $project->assigned_expert_id === $user->id);
    }

    /**
     * Check if user can modify project
     */
    private function canModifyProject($user, Project $project): bool
    {
        return $user->hasRole(['admin', 'super_admin']) ||
               ($user->hasRole('student') && $project->student_id === $user->id);
    }
}
