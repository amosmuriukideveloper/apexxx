<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
    case REJECTED = 'rejected';
    
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::PENDING => 'Pending',
            self::SUSPENDED => 'Suspended',
            self::REJECTED => 'Rejected',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::PENDING => 'warning',
            self::SUSPENDED => 'danger',
            self::REJECTED => 'danger',
        };
    }
}
