<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EmailSettings extends Settings
{
    public string $driver;
    public ?string $host;
    public ?int $port;
    public ?string $username;
    public ?string $password;
    public ?string $encryption;
    public string $from_address;
    public string $from_name;
    public bool $is_active;

    public static function group(): string
    {
        return 'email';
    }

    public static function defaults(): array
    {
        return [
            'driver' => 'smtp',
            'host' => null,
            'port' => 587,
            'username' => null,
            'password' => null,
            'encryption' => 'tls',
            'from_address' => '',
            'from_name' => 'Academic Platform',
            'is_active' => false,
        ];
    }
}
