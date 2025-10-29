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

class TutorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tutor')
            ->path('tutor')
            ->login()
            ->registration()
            ->brandName('Tutor Dashboard')
            ->brandLogo(asset('images/logo.png'))
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::Green,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Tutor/Resources'), for: 'App\\Filament\\Tutor\\Resources')
            ->discoverPages(in: app_path('Filament/Tutor/Pages'), for: 'App\\Filament\\Tutor\\Pages')
            ->pages([
                \App\Filament\Tutor\Pages\Dashboard::class,
                \App\Filament\Tutor\Pages\PendingRequests::class,
                \App\Filament\Tutor\Pages\ScheduledSessions::class,
                \App\Filament\Tutor\Pages\CompletedSessions::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tutor/Widgets'), for: 'App\\Filament\\Tutor\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                'Sessions',
                'Students',
                'Schedule',
                'Earnings',
                'Profile',
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
            ->authGuard('web')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s');
    }
}
