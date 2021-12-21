<?php

namespace Chrisguitarguy\RequestId;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

final class TestKernel extends Kernel
{
    private $configFile;

    public function registerBundles(): iterable
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Chrisguitarguy\RequestId\ChrisguitarguyRequestIdBundle(),
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir()."/config/config.yml");
    }

    public function getLogDir(): string
    {
        return __DIR__.'/tmp';
    }

    public function getCacheDir(): string
    {
        return __DIR__.'/tmp';
    }

    public function getProjectDir(): string
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
