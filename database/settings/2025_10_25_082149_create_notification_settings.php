<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Application Notifications
        $this->migrator->add('notification.application_submitted_email', true);
        $this->migrator->add('notification.application_submitted_database', true);
        $this->migrator->add('notification.application_approved_email', true);
        $this->migrator->add('notification.application_approved_database', true);
        $this->migrator->add('notification.application_rejected_email', true);
        $this->migrator->add('notification.application_rejected_database', true);

        // Project Notifications
        $this->migrator->add('notification.project_assigned_email', true);
        $this->migrator->add('notification.project_assigned_database', true);
        $this->migrator->add('notification.project_submitted_email', true);
        $this->migrator->add('notification.project_submitted_database', true);
        $this->migrator->add('notification.project_completed_email', true);
        $this->migrator->add('notification.project_completed_database', true);

        // Tutoring Notifications
        $this->migrator->add('notification.session_booked_email', true);
        $this->migrator->add('notification.session_booked_database', true);
        $this->migrator->add('notification.session_confirmed_email', true);
        $this->migrator->add('notification.session_confirmed_database', true);

        // Payment Notifications
        $this->migrator->add('notification.payment_received_email', true);
        $this->migrator->add('notification.payment_received_database', true);
        $this->migrator->add('notification.payout_processed_email', true);
        $this->migrator->add('notification.payout_processed_database', true);
    }
};
