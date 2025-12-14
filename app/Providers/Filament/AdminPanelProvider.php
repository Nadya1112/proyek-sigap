<?php

namespace App\Providers\Filament;

use App\Filament\Resources\DokumenRegulasiResource;
use App\Filament\Resources\KecamatanResource;
use App\Filament\Resources\KelurahanResource;
use App\Filament\Resources\KomplekResource;
use App\Filament\Resources\PengaduanResource;
use App\Filament\Resources\ProposalResource;
use App\Filament\Resources\PsuResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                DokumenRegulasiResource::class,
                KecamatanResource::class,
                KelurahanResource::class,
                KomplekResource::class,
                PengaduanResource::class,
                ProposalResource::class,
                PsuResource::class,
                UserResource::class,
            ])
            // ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                /** @var \App\Models\User $user */
                $user = auth()->user();

                if ($user->isSuperAdmin()) {
                    return $builder->groups([
                        NavigationGroup::make('Manajemen Admin')
                            ->items([
                                NavigationItem::make('Dashboard')
                                    ->icon('heroicon-o-home')
                                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                    ->url(fn (): string => Pages\Dashboard::getUrl()),
                                ...UserResource::getNavigationItems(),
                                ...DokumenRegulasiResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Informasi FASUM')
                            ->items([
                                ...KecamatanResource::getNavigationItems(),
                                ...KelurahanResource::getNavigationItems(),
                                ...KomplekResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Pelayanan Publik')
                            ->items([
                                ...PengaduanResource::getNavigationItems(),
                                ...ProposalResource::getNavigationItems(),
                            ]),
                    ]);
                }

                // For other roles
                $informasiFasumItems = [];
                if (in_array($user->role, [User::ROLE_STAFF, User::ROLE_JF_PSU, User::ROLE_KABID, User::ROLE_KADIS])) {
                    $informasiFasumItems = [
                        ...KecamatanResource::getNavigationItems(),
                        ...KelurahanResource::getNavigationItems(),
                        ...KomplekResource::getNavigationItems(),
                    ];
                } else {
                    $informasiFasumItems = [
                        ...KomplekResource::getNavigationItems(),
                    ];
                }

                return $builder->groups([
                    NavigationGroup::make()->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Pages\Dashboard::getUrl()),
                    ]),
                    NavigationGroup::make('Informasi Fasum')
                        ->items($informasiFasumItems),
                    NavigationGroup::make('Pelayanan Publik')
                        ->items([
                            ...PengaduanResource::getNavigationItems(),
                            ...ProposalResource::getNavigationItems(),
                        ]),
                ]);
            });
    }
}
