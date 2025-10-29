<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('email.driver', 'smtp');
        $this->migrator->add('email.host', 'smtp.gmail.com');
        $this->migrator->add('email.port', 587);
        $this->migrator->add('email.username', '');
        $this->migrator->add('email.password', '');
        $this->migrator->add('email.encryption', 'tls');
        $this->migrator->add('email.from_address', '');
        $this->migrator->add('email.from_name', 'Scholars Quiver');
        $this->migrator->add('email.is_active', true);
    }
};
