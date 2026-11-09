<?php

namespace App\Providers;

use App\Models\NewsPost;
use App\Services\Audit\AuditWriter;
use App\Support\I18n\LocaleCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuditWriter::class, function () {
            return new AuditWriter(DB::connection('mysql_audit'));
        });
    }

    public function boot(): void
    {
        View::composer(['landing', 'public.*', 'tracking.*'], function ($view): void {
            $view->with('availableLocales', app(LocaleCatalog::class)->all());
        });

        View::composer('public.footer', function ($view): void {
            $view->with('footerNews', NewsPost::query()
                ->visibleToPublic()
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(['title', 'slug', 'category', 'published_at']));
        });
    }
}
