<?php

namespace App\Providers\Filament;

use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Support\Colors\Color;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class CmsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('cms')
            ->login()
            ->registration()
            ->favicon(asset('favicon.svg'))
            ->brandLogo(fn() => view('filament.brand'))
            ->brandName(config('app.brand_name', config('app.name')))
            ->brandLogoHeight('2rem')
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Green,
            ])
            ->navigationGroups([
                'Master Data',
                'Transactions',
            ])
            ->navigationItems([
                NavigationItem::make('Lihat Website')
                    ->icon('heroicon-o-globe-alt')
                    ->url(url('/'))
                    ->openUrlInNewTab()
                    ->sort(PHP_INT_MAX),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin::make()->color('#FF0000'),
                LocalLogins::make(),
                EnvironmentIndicatorPlugin::make()
                    ->visible(true)  // pastikan plugin tidak disembunyikan
                    ->color(fn() => match (app()->environment()) {
                        'production' => Color::Red,
                        'staging' => Color::Red,
                        default => Color::Green,
                    }),
                GlobalSearchModalPlugin::make(),
            ]);
    }
}
