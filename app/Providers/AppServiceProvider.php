<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public $bindings = [
        \Backpack\CRUD\app\Http\Controllers\Auth\LoginController::class => \App\Http\Controllers\Auth\LoginController::class,
        \Backpack\CRUD\app\Http\Controllers\Auth\RegisterController::class => \App\Http\Controllers\Auth\RegisterController::class,
        \Backpack\CRUD\app\Http\Controllers\Auth\ResetPasswordController::class => \App\Http\Controllers\Auth\ResetPasswordController::class,
        \Backpack\CRUD\app\Http\Controllers\Auth\ForgotPasswordController::class => \App\Http\Controllers\Auth\ForgotPasswordController::class,
    ];

    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \GemaDigital\Macros\RequestMacros::register();
        \GemaDigital\Macros\BuilderMacros::register();
        \GemaDigital\Macros\DBMacros::register();
    }
}
