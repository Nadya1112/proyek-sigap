<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Pengaduan;
use App\Models\Proposal;
use App\Observers\PengaduanObserver;
use App\Observers\ProposalObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Pengaduan::observe(PengaduanObserver::class);
        Proposal::observe(ProposalObserver::class);
    }
}
