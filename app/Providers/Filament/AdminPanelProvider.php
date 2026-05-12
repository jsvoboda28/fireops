<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            
            ->brandName('FireOps')
            ->brandLogo(asset('images/fireops-logo.svg'))
            ->brandLogoHeight('2.5rem')
            
            ->colors([
                'primary' => Color::Red,
                'danger' => Color::Red,
                'warning' => Color::Orange,
                'success' => Color::Emerald,
                'info' => Color::Sky,
                'gray' => Color::Slate,
            ])
            
            ->font('Inter')
            
            ->sidebarCollapsibleOnDesktop()
            
            ->navigationGroups([
                'Operativa',
                'Resursi',
                'Postavke sustava',
            ])
            
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn () => Blade::render('
                    <div class="flex items-center gap-2 px-3 py-1 text-xs font-semibold">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-gray-600 dark:text-gray-300">Sustav operativan</span>
                    </div>
                ')
            )
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Postavke sustava')
                    ->navigationSort(99)
                    ->navigationLabel('Uloge'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}