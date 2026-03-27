<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        // BREVO API TRANSPORTUNU BURADA KAYDEDİYORUZ
        \Illuminate\Support\Facades\Mail::extend('sendinblue', function (array $config) {
            return (new \Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory)->create(
                new \Symfony\Component\Mailer\Transport\Dsn(
                    'sendinblue+api',
                    'default',
                    env('MAIL_PASSWORD') // Render'daki o upuzun xkeysib... kodunu okur
                )
            );
        });
    }
}