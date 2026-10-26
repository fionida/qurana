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
        $this->ensurePublicStorageDirectories();

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

    private function ensurePublicStorageDirectories(): void
    {
        foreach ([
            public_path('storage'),
            public_path('storage/pas-foto'),
            public_path('storage/bukti-transfer'),
            public_path('storage/document-templates'),
            storage_path('app/public'),
            storage_path('app/public/pas-foto'),
        ] as $dir) {
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
    }
}
