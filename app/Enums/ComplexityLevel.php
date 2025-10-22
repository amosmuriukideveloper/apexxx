<?php

namespace App\Enums;

enum ComplexityLevel: string
{
    case BASIC = 'basic';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    
    public function label(): string
    {
        return match($this) {
            self::BASIC => 'Basic',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
        };
    }
}
