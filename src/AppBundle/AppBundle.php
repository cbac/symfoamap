<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bundle\AsseticBundle;
class AppBundle extends Bundle
{
    public function registerBundles()
    {
        $bundles = array(
            new AsseticBundle(),
        );

    }
}
