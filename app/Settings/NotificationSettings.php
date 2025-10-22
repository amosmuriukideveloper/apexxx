<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NotificationSettings extends Settings
{
    // Application notifications
    public bool $application_submitted_email;
    public bool $application_submitted_database;
    public bool $application_approved_email;
    public bool $application_approved_database;
    public bool $application_rejected_email;
    public bool $application_rejected_database;

    // Project notifications
    public bool $project_assigned_email;
    public bool $project_assigned_database;
    public bool $project_submitted_email;
    public bool $project_submitted_database;
    public bool $project_completed_email;
    public bool $project_completed_database;

    // Tutoring notifications
    public bool $tutoring_request_email;
    public bool $tutoring_request_database;
    public bool $tutoring_accepted_email;
    public bool $tutoring_accepted_database;
    public bool $tutoring_completed_email;
    public bool $tutoring_completed_database;

    // Payment notifications
    public bool $payment_received_email;
    public bool $payment_received_database;
    public bool $payout_processed_email;
    public bool $payout_processed_database;

    // System notifications
    public bool $system_maintenance_email;
    public bool $system_maintenance_database;

    public static function group(): string
    {
        return 'notification';
    }

    public static function defaults(): array
    {
        return [
            // Application notifications
            'application_submitted_email' => true,
            'application_submitted_database' => true,
            'application_approved_email' => true,
            'application_approved_database' => true,
            'application_rejected_email' => true,
            'application_rejected_database' => true,

            // Project notifications
            'project_assigned_email' => true,
            'project_assigned_database' => true,
            'project_submitted_email' => true,
            'project_submitted_database' => true,
            'project_completed_email' => true,
            'project_completed_database' => true,

            // Tutoring notifications
            'tutoring_request_email' => true,
            'tutoring_request_database' => true,
            'tutoring_accepted_email' => true,
            'tutoring_accepted_database' => true,
            'tutoring_completed_email' => true,
            'tutoring_completed_database' => true,

            // Payment notifications
            'payment_received_email' => true,
            'payment_received_database' => true,
            'payout_processed_email' => true,
            'payout_processed_database' => true,

            // System notifications
            'system_maintenance_email' => true,
            'system_maintenance_database' => true,
        ];
    }
}
