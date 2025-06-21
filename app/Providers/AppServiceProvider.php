<?php

namespace App\Providers;

use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public $bindings = [
        \Backpack\PermissionManager\app\Http\Controllers\UserCrudController::class => \GemaDigital\Http\Controllers\Admin\UserCrudController::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->singleton(FakerGenerator::class, function () {
                $faker = \Faker\Factory::create();
                $faker->addProvider(new \App\Providers\FakerProvider($faker));

                return $faker;
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Blade::directive('svg', function ($arguments) {
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

            $svg = new \DOMDocument;
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

        \Illuminate\Database\Schema\Blueprint::macro('foreignTerritoryId', function (string $column): \Illuminate\Database\Schema\ColumnDefinition {
            $columnDefinition = $this->string($column, 6);

            $this->foreign($column)
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');

            return $columnDefinition;
        });
    }
}
