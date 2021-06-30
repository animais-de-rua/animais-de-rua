<?php

namespace App\Helpers;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postInstall()
    {
        switch (DIRECTORY_SEPARATOR) {
            case '/': // unix
                exec('ln -s ../../vendor/almasaeed2010/adminlte public/vendor');
                break;
            case '\\': // windows
                exec('mklink /J "public\vendor\adminlte" "vendor\almasaeed2010\adminlte"');
                break;
        }
    }
}
