<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $notifications = \App\Models\Notification::where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get();
                $unreadCount = $notifications->where('is_read', false)->count();
                $view->with(compact('notifications', 'unreadCount'));
            }
        });
    }
}
