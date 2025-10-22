<?php

namespace App\Traits;

trait HasRoleHelpers
{
    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is an expert
     */
    public function isExpert(): bool
    {
        return $this->hasRole('expert');
    }

    /**
     * Check if user is a tutor
     */
    public function isTutor(): bool
    {
        return $this->hasRole('tutor');
    }

    /**
     * Check if user is a content creator
     */
    public function isContentCreator(): bool
    {
        return $this->hasRole('content_creator');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user has any admin role (admin or super_admin)
     */
    public function isAnyAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Check if user is a specialist (expert, tutor, or content creator)
     */
    public function isSpecialist(): bool
    {
        return $this->hasAnyRole(['expert', 'tutor', 'content_creator']);
    }

    /**
     * Get user's primary role name
     */
    public function getPrimaryRole(): ?string
    {
        return $this->roles->first()?->name;
    }

    /**
     * Check if user can manage projects
     */
    public function canManageProjects(): bool
    {
        return $this->can('assign_projects') || $this->can('approve_projects');
    }

    /**
     * Check if user can manage courses
     */
    public function canManageCourses(): bool
    {
        return $this->can('create_courses') || $this->can('approve_courses');
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers(): bool
    {
        return $this->can('view_users') || $this->can('edit_users');
    }

    /**
     * Check if user can view analytics
     */
    public function canViewAnalytics(): bool
    {
        return $this->can('view_analytics') || $this->can('view_performance_analytics');
    }
}
