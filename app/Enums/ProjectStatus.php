<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case AWAITING_ASSIGNMENT = 'awaiting_assignment';
    case ASSIGNED = 'assigned';
    case IN_PROGRESS = 'in_progress';
    case UNDER_REVIEW = 'under_review';
    case REVISION_REQUIRED = 'revision_required';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    
    public function label(): string
    {
        return match($this) {
            self::AWAITING_ASSIGNMENT => 'Awaiting Assignment',
            self::ASSIGNED => 'Assigned',
            self::IN_PROGRESS => 'In Progress',
            self::UNDER_REVIEW => 'Under Review',
            self::REVISION_REQUIRED => 'Revision Required',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::AWAITING_ASSIGNMENT => 'warning',
            self::ASSIGNED => 'info',
            self::IN_PROGRESS => 'primary',
            self::UNDER_REVIEW => 'secondary',
            self::REVISION_REQUIRED => 'danger',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
