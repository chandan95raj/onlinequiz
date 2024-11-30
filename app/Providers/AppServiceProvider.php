<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

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
        // Customize email verification notification
        VerifyEmail::toMailUsing(function ($notifiable) {
            return (new MailMessage)
                ->line('Thanks for signing up!')
                ->action('Verify Email', URL::temporarySignedRoute(
                    'verification.verify', now()->addMinutes(60), ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
                ))
                ->line('If you did not sign up, no further action is required.');
        });
    }
}
