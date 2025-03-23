<?php

namespace App\Providers;

use DOMDocument;
use Illuminate\Support\Facades\Blade;
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

        Blade::directive('svg', function ($arguments) {
            // Parse the passed arguments: path, class, style
            [$path, $class, $style] = array_pad(explode(',', trim($arguments.',,', '() ')), 2, '');
            $path = trim($path, "' ");
            $class = trim($class, "' ");
            $style = trim($style, "' ");

            // Load the SVG file
            $svgPath = public_path($path);

            if (! file_exists($svgPath)) {
                return '';  // Return an empty string if the file doesn't exist
            }

            $svg = new DOMDocument;
            $svg->load($svgPath);
            $svgElement = $svg->documentElement;

            // Add the class and style attributes if they are provided
            if ($class && $svgElement) {
                $svgElement->setAttribute('class', $class);
            }

            if ($style && $svgElement) {
                $svgElement->setAttribute('style', $style);
            }

            // Return the modified SVG XML
            return $svg->saveXML($svgElement);
        });
    }
}
