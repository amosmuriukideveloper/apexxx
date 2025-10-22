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
            ->brandName('Tutor Panel')
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
            ])
            ->discoverWidgets(in: app_path('Filament/Tutor/Widgets'), for: 'App\\Filament\\Tutor\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                'Sessions' => [
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                    'sort' => 1,
                ],
                'Students' => [
                    'icon' => 'heroicon-o-users',
                    'sort' => 2,
                ],
                'Schedule' => [
                    'icon' => 'heroicon-o-calendar',
                    'sort' => 3,
                ],
                'Earnings' => [
                    'icon' => 'heroicon-o-banknotes',
                    'sort' => 4,
                ],
                'Profile' => [
                    'icon' => 'heroicon-o-user',
                    'sort' => 5,
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
            ->authGuard('web');
    }
}
