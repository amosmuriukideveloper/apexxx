<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Projects
        $projectPermissions = [
            'create_projects',
            'view_projects',
            'edit_projects',
            'delete_projects',
            'assign_projects',
            'submit_projects',
            'approve_projects',
            'reject_projects',
            'request_revisions',
            'track_project_status',
            'upload_deliverables',
            'download_project_materials',
        ];

        // Create permissions for Tutoring
        $tutoringPermissions = [
            'create_tutoring_sessions',
            'view_tutoring_sessions',
            'edit_tutoring_sessions',
            'delete_tutoring_sessions',
            'book_tutoring_sessions',
            'schedule_tutoring_sessions',
            'assign_tutoring_requests',
            'manage_availability',
            'upload_session_notes',
            'track_session_attendance',
            'view_student_profiles',
        ];

        // Create permissions for Courses (Knowledge Hub)
        $coursePermissions = [
            'create_courses',
            'view_courses',
            'edit_courses',
            'delete_courses',
            'enroll_courses',
            'approve_courses',
            'reject_courses',
            'manage_course_content',
            'upload_videos',
            'create_quizzes',
            'view_course_analytics',
            'manage_course_pricing',
            'moderate_course_content',
        ];

        // Create permissions for Wallet & Payments
        $paymentPermissions = [
            'view_wallet',
            'manage_wallet',
            'view_transactions',
            'process_payments',
            'track_earnings',
            'manage_payment_methods',
            'view_payment_analytics',
            'manage_payment_gateway',
        ];

        // Create permissions for User Management
        $userPermissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',
            'manage_user_roles',
            'view_user_analytics',
            'moderate_users',
            'manage_user_disputes',
        ];

        // Create permissions for Analytics & Reports
        $analyticsPermissions = [
            'view_analytics',
            'view_performance_analytics',
            'view_platform_analytics',
            'view_earnings_reports',
            'view_course_reports',
            'view_project_reports',
            'view_user_reports',
        ];

        // Create permissions for Communication
        $communicationPermissions = [
            'send_messages',
            'view_messages',
            'moderate_messages',
            'manage_notifications',
        ];

        // Create permissions for Reviews & Ratings
        $reviewPermissions = [
            'create_reviews',
            'view_reviews',
            'moderate_reviews',
            'respond_to_reviews',
        ];

        // Create permissions for System Management
        $systemPermissions = [
            'manage_system_config',
            'manage_api_keys',
            'manage_security_settings',
            'manage_database',
            'view_system_logs',
        ];

        // Create Shield-specific permissions for Filament resources
        $shieldPermissions = [
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',
            'force_delete_user',
            'force_delete_any_user',
            'restore_user',
            'restore_any_user',
            'replicate_user',
            'reorder_user',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $projectPermissions,
            $tutoringPermissions,
            $coursePermissions,
            $paymentPermissions,
            $userPermissions,
            $analyticsPermissions,
            $communicationPermissions,
            $reviewPermissions,
            $systemPermissions,
            $shieldPermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // 1. Student Role
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'create_projects',
            'view_projects',
            'submit_projects',
            'request_revisions',
            'track_project_status',
            'download_project_materials',
            'book_tutoring_sessions',
            'view_tutoring_sessions',
            'enroll_courses',
            'view_courses',
            'view_wallet',
            'manage_wallet',
            'view_transactions',
            'manage_payment_methods',
            'send_messages',
            'view_messages',
            'create_reviews',
            'view_reviews',
        ]);

        // 2. Expert Role
        $expertRole = Role::create(['name' => 'expert']);
        $expertRole->givePermissionTo([
            'view_projects',
            'edit_projects',
            'upload_deliverables',
            'track_project_status',
            'download_project_materials',
            'track_earnings',
            'view_transactions',
            'manage_availability',
            'view_performance_analytics',
            'send_messages',
            'view_messages',
            'respond_to_reviews',
            'view_reviews',
        ]);

        // 3. Tutor Role
        $tutorRole = Role::create(['name' => 'tutor']);
        $tutorRole->givePermissionTo([
            'view_tutoring_sessions',
            'edit_tutoring_sessions',
            'schedule_tutoring_sessions',
            'manage_availability',
            'upload_session_notes',
            'track_session_attendance',
            'view_student_profiles',
            'track_earnings',
            'view_transactions',
            'view_performance_analytics',
            'send_messages',
            'view_messages',
            'respond_to_reviews',
            'view_reviews',
        ]);

        // 4. Content Creator Role
        $contentCreatorRole = Role::create(['name' => 'content_creator']);
        $contentCreatorRole->givePermissionTo([
            'create_courses',
            'view_courses',
            'edit_courses',
            'manage_course_content',
            'upload_videos',
            'create_quizzes',
            'view_course_analytics',
            'manage_course_pricing',
            'track_earnings',
            'view_transactions',
            'send_messages',
            'view_messages',
            'respond_to_reviews',
            'view_reviews',
        ]);

        // 5. Admin Role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view_projects',
            'edit_projects',
            'assign_projects',
            'approve_projects',
            'reject_projects',
            'assign_tutoring_requests',
            'view_tutoring_sessions',
            'approve_courses',
            'reject_courses',
            'moderate_course_content',
            'process_payments',
            'view_payment_analytics',
            'view_users',
            'edit_users',
            'moderate_users',
            'manage_user_disputes',
            'view_analytics',
            'view_platform_analytics',
            'view_earnings_reports',
            'view_course_reports',
            'view_project_reports',
            'view_user_reports',
            'moderate_messages',
            'manage_notifications',
            'moderate_reviews',
            // Shield permissions for Filament admin panel
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',
        ]);

        // 6. Super Admin Role
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions have been seeded successfully!');
    }
}
