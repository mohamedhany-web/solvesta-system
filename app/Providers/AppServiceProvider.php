<?php

namespace App\Providers;

use App\Models\ClientMeetingRequest;
use App\Models\ClientSharedDocument;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Observers\FinancialInvoiceObserver;
use App\Observers\InvoiceObserver;
use Illuminate\Support\Facades\Route;
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
    }
}