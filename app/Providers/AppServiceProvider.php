<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set up Faker default languages
        $this->app->singleton(\Faker\Generator::class, function () {
            return \Faker\Factory::create('pt_PT');
        });

        \Blade::directive('svg', function ($arguments) {
            list($path, $class, $style) = array_pad(explode(',', trim($arguments . ',,', '() ')), 2, '');
            $path = trim($path, "' ");
            $class = trim($class, "' ");
            $style = trim($style, "' ");

            $svg = new \DOMDocument();
            $svg->load(public_path($path));
            $svg->documentElement->setAttribute('class', $class);
            $svg->documentElement->setAttribute('style', $style);
            return $svg->saveXML($svg->documentElement);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
