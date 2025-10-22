<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('platform')
            ->path('/platform')
            ->login()
            ->brandName('Apex Scholars')
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                'Projects' => [
                    'icon' => 'heroicon-o-briefcase',
                    'sort' => 1,
                ],
                'Learning' => [
                    'icon' => 'heroicon-o-academic-cap',
                    'sort' => 2,
                ],
                'Tutoring' => [
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                    'sort' => 3,
                ],
                'Financial' => [
                    'icon' => 'heroicon-o-banknotes',
                    'sort' => 4,
                ],
                'Communication' => [
                    'icon' => 'heroicon-o-envelope',
                    'sort' => 5,
                ],
                'Analytics' => [
                    'icon' => 'heroicon-o-chart-bar',
                    'sort' => 6,
                ],
                'User Management' => [
                    'icon' => 'heroicon-o-users',
                    'sort' => 7,
                ],
                'System' => [
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'sort' => 8,
                ],
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
            ]);
    }
}
