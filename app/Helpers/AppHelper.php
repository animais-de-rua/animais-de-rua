<?php

if (! function_exists('api')) {
    function api() {
        return app('App\Http\Controllers\Admin\APICrudController');
    }
}