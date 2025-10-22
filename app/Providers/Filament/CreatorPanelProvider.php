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

class CreatorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('creator')
            ->path('creator')
            ->login()
            ->brandName('Creator Studio')
            ->brandLogo(asset('images/logo.png'))
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::Purple,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Creator/Resources'), for: 'App\\Filament\\Creator\\Resources')
            ->discoverPages(in: app_path('Filament/Creator/Pages'), for: 'App\\Filament\\Creator\\Pages')
            ->pages([
                \App\Filament\Creator\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Creator/Widgets'), for: 'App\\Filament\\Creator\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                'My Content' => [
                    'icon' => 'heroicon-o-academic-cap',
                    'sort' => 1,
                ],
                'Analytics' => [
                    'icon' => 'heroicon-o-chart-bar',
                    'sort' => 2,
                ],
                'Earnings' => [
                    'icon' => 'heroicon-o-banknotes',
                    'sort' => 3,
                ],
                'Profile' => [
                    'icon' => 'heroicon-o-user',
                    'sort' => 4,
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
