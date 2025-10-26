<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Seed subjects before other content
        $this->call(SubjectSeeder::class);

        // Create a super admin user
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@apexscholars.com',
        ]);
        $superAdmin->assignRole('super_admin');

        // Create test users for each role
        $student = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);
        $student->assignRole('student');

        $expert = User::factory()->create([
            'name' => 'Test Expert',
            'email' => 'expert@example.com',
        ]);
        $expert->assignRole('expert');

        $tutor = User::factory()->create([
            'name' => 'Test Tutor',
            'email' => 'tutor@example.com',
        ]);
        $tutor->assignRole('tutor');

        $contentCreator = User::factory()->create([
            'name' => 'Test Content Creator',
            'email' => 'creator@example.com',
        ]);
        $contentCreator->assignRole('content_creator');

        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Create additional users for testing
        User::factory(20)->create()->each(function ($user) {
            $roles = ['student', 'expert', 'tutor', 'content_creator'];
            $user->assignRole(fake()->randomElement($roles));
        });

        // Seed content and transactions
        // $this->call([
        //     ProjectSeeder::class,
        //     CourseSeeder::class,
        //     TutoringSeeder::class,
        //     StudyResourceSeeder::class,
        //     WalletSeeder::class,
        // ]);
    }
}
