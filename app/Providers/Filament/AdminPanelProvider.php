<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
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
            ->brandName('Dana Kampung Mbu')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\StatistikDanaWidget::class,
                \App\Filament\Widgets\GrafikTransaksiWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render(<<<'BLADE'
                    <style>
                        [x-cloak] { display: none !important; }
                        .fi-sidebar, .fi-topbar, .fi-main { pointer-events: auto; }
                        body:not(.fi-modal-open) .fi-modal-close-overlay[style*="display: none"] {
                            pointer-events: none !important;
                        }
                    </style>
                BLADE)
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render(<<<'BLADE'
                    <script>
                        (() => {
                            const isVisible = (element) => {
                                if (!element) return false;
                                const style = window.getComputedStyle(element);
                                return style.display !== 'none' && style.visibility !== 'hidden' && Number(style.opacity) !== 0;
                            };

                            const removeOrphanedFilamentOverlays = () => {
                                const visibleDialog = Array.from(document.querySelectorAll('[role="dialog"], .fi-modal-window'))
                                    .some(isVisible);

                                if (visibleDialog) return;

                                document.querySelectorAll('.fi-modal-close-overlay, .fi-modal-overlay, [data-filament-modal-overlay]')
                                    .forEach((overlay) => {
                                        if (isVisible(overlay)) {
                                            overlay.style.display = 'none';
                                            overlay.style.pointerEvents = 'none';
                                        }
                                    });

                                document.documentElement.classList.remove('overflow-hidden');
                                document.body.classList.remove('overflow-hidden');
                            };

                            document.addEventListener('livewire:navigated', removeOrphanedFilamentOverlays);
                            document.addEventListener('livewire:initialized', removeOrphanedFilamentOverlays);
                            window.addEventListener('pageshow', removeOrphanedFilamentOverlays);
                            window.addEventListener('keydown', (event) => {
                                if (event.key === 'Escape') {
                                    setTimeout(removeOrphanedFilamentOverlays, 100);
                                }
                            });
                        })();
                    </script>
                BLADE)
            )
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
            ]);
    }
}
