<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\TutoringController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\StudyResourceController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/register/{type}', [AuthController::class, 'registerByType'])->where('type', 'student|expert|tutor|creator');

// Public course and resource browsing
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::get('/study-resources', [StudyResourceController::class, 'index']);
Route::get('/study-resources/{resource}', [StudyResourceController::class, 'show']);

// Payment webhooks (public but secured by signature verification)
Route::post('/webhooks/mpesa', [PaymentController::class, 'mpesaWebhook']);
Route::post('/webhooks/paypal', [PaymentController::class, 'paypalWebhook']);
Route::post('/webhooks/pesapal', [PaymentController::class, 'pesapalWebhook']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // User authentication
    Route::get('/user', function (Request $request) {
        return $request->user()->load('roles', 'permissions');
    });
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::post('/projects/{project}/submit', [ProjectController::class, 'submit']);
    Route::post('/projects/{project}/request-revision', [ProjectController::class, 'requestRevision']);
    Route::post('/projects/{project}/approve', [ProjectController::class, 'approve']);
    Route::post('/projects/{project}/messages', [ProjectController::class, 'sendMessage']);
    Route::get('/projects/{project}/messages', [ProjectController::class, 'getMessages']);

    // Courses
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);
    Route::get('/courses/{course}/progress', [CourseController::class, 'getProgress']);
    Route::post('/courses/{course}/lectures/{lecture}/complete', [CourseController::class, 'completeLecture']);
    Route::post('/courses/{course}/review', [CourseController::class, 'addReview']);

    // Tutoring
    Route::apiResource('tutoring-requests', TutoringController::class);
    Route::post('/tutoring-requests/{request}/accept', [TutoringController::class, 'accept']);
    Route::post('/tutoring-requests/{request}/decline', [TutoringController::class, 'decline']);
    Route::get('/tutoring-sessions', [TutoringController::class, 'getSessions']);
    Route::post('/tutoring-sessions/{session}/feedback', [TutoringController::class, 'addFeedback']);

    // Study Resources
    Route::post('/study-resources/{resource}/purchase', [StudyResourceController::class, 'purchase']);
    Route::get('/study-resources/{resource}/download', [StudyResourceController::class, 'download']);
    Route::post('/study-resources/{resource}/review', [StudyResourceController::class, 'addReview']);

    // Wallet and Payments
    Route::get('/wallet', [WalletController::class, 'show']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    Route::post('/payments/process', [PaymentController::class, 'process']);
    Route::get('/payments/{payment}/status', [PaymentController::class, 'status']);

    // Role-specific routes
    Route::middleware(['role:expert'])->group(function () {
        Route::get('/expert/dashboard', [ProjectController::class, 'expertDashboard']);
        Route::get('/expert/projects', [ProjectController::class, 'expertProjects']);
    });

    Route::middleware(['role:tutor'])->group(function () {
        Route::get('/tutor/dashboard', [TutoringController::class, 'tutorDashboard']);
        Route::get('/tutor/sessions', [TutoringController::class, 'tutorSessions']);
    });

    Route::middleware(['role:content_creator'])->group(function () {
        Route::get('/creator/dashboard', [CourseController::class, 'creatorDashboard']);
        Route::apiResource('creator/courses', CourseController::class, ['as' => 'creator']);
        Route::apiResource('creator/study-resources', StudyResourceController::class, ['as' => 'creator']);
    });

    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', [ProjectController::class, 'studentDashboard']);
        Route::get('/student/courses', [CourseController::class, 'studentCourses']);
        Route::get('/student/tutoring', [TutoringController::class, 'studentSessions']);
    });

    Route::middleware(['role:admin|super_admin'])->group(function () {
        Route::get('/admin/stats', function () {
            return response()->json([
                'users' => \App\Models\User::count(),
                'projects' => \App\Models\Project::count(),
                'courses' => \App\Models\Course::count(),
                'revenue' => \App\Models\Transaction::where('type', 'payment')->sum('amount'),
            ]);
        });
    });
});
