<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer([
            'layouts.public',
            'auth.login',
            'portal.*',
            'registration.*',
            'status-check.*',
        ], function ($view) {
            $view->with('siteLogo', Setting::logoUrl());
        });
    }
}
