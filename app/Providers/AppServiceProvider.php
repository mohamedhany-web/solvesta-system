<?php

namespace App\Providers;

use App\Models\ClientMeetingRequest;
use App\Models\ClientSharedDocument;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Observers\FinancialInvoiceObserver;
use App\Observers\InvoiceObserver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invoice::observe(InvoiceObserver::class);
        FinancialInvoice::observe(FinancialInvoiceObserver::class);

        Route::bind('meetingRequest', function (string $value) {
            return ClientMeetingRequest::where('id', $value)->firstOrFail();
        });

        Route::bind('sharedDocument', function (string $value) {
            return ClientSharedDocument::where('id', $value)->firstOrFail();
        });

        $this->configurePublicAssetUrl();
    }

    /**
     * Ensure CSS/JS URLs match the browser host (fixes broken design when
     * APP_URL is localhost but the site is opened via 127.0.0.1 or a live domain).
     */
    protected function configurePublicAssetUrl(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        if (config('app.asset_url')) {
            return;
        }

        $request = request();
        if (! $request) {
            return;
        }

        $liveRoot = rtrim($request->root(), '/');
        $configured = rtrim((string) config('app.url'), '/');

        if ($liveRoot === '') {
            return;
        }

        $localDefaults = [
            'http://localhost',
            'https://localhost',
            'http://127.0.0.1',
            'https://127.0.0.1',
        ];

        if ($configured === '' || $configured !== $liveRoot || in_array($configured, $localDefaults, true)) {
            URL::forceRootUrl($liveRoot);
        }
    }
}