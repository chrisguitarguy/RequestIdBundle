<?php declare(strict_types=1);

/*
 * This file is part of chrisguitarguy/request-id-bundle

 * Copyright (c) Christopher Davis <http://christopherdavis.me>
 *
 * For full copyright information see the LICENSE file distributed
 * with this source code.
 *
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\RequestId;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

final class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Chrisguitarguy\RequestId\ChrisguitarguyRequestIdBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir()."/config/config.yml");
    }

    public function getLogDir()
    {
        return __DIR__.'/tmp';
    }

    public function getCacheDir()
    {
        return __DIR__.'/tmp';
    }

    public function getProjectDir()
    {
        return __DIR__;
    }

    public function getLogs()
    {
        return $this->getContainer()->get('log.memory_handler')->getLogs();
    }

    public function boot()
    {
        // clear up the cached files
        foreach (glob(__DIR__.'/app/tmp/*') as $fn) {
            if ('.' !== basename($fn)[0]) {
                @unlink($fn);
            }
        }

        parent::boot();
    }
}
