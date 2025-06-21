<?php

namespace App\Helpers;

use GemaDigital\Helpers\ComposerScripts as DefaultComposerScripts;
use Override;

class ComposerScripts extends DefaultComposerScripts
{
    /**
     * Post install Unix
     */
    #[Override]
    public static function postInstallUnix(): void
    {
        //
    }

    /**
     * Post install Windows
     */
    #[Override]
    public static function postInstallWindows(): void
    {
        //
    }
}
