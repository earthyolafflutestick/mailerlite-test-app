<?php

namespace App\Providers;

use App\Services\ApiKeyService;
use App\Services\MailerLiteService;
use Illuminate\Support\ServiceProvider;

class MailerLiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(MailerLiteService::class, function ($app) {
            $mailerLiteService = new MailerLiteService();
            $mailerLiteService->setApiKey(ApiKeyService::get());
            $mailerLiteService->setTimeout(5);

            return $mailerLiteService;
        });
    }
}
