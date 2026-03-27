<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Kendi tasarımımızı (emails.dogrulama) kullanmasını söylüyoruz
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Yalova Üni. Öğrenci Kayıt Doğrulaması')
                ->view('emails.dogrulama', ['url' => $url]);
        });
    }
}