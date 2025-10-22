<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'message',
        'context',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public static function log(string $level, string $message, array $context = [])
    {
        return static::create([
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'user_id' => auth()->id(),
            'user_type' => auth()->user() ? get_class(auth()->user()) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function info(string $message, array $context = [])
    {
        return static::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        return static::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        return static::log('error', $message, $context);
    }

    public static function critical(string $message, array $context = [])
    {
        return static::log('critical', $message, $context);
    }
}
