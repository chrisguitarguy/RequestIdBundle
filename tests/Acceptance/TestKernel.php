<?php
declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Acceptance;

use DR\SymfonyRequestId\RequestIdBundle;
use Exception;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @return iterable<int|string, BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new MonologBundle(),
            new RequestIdBundle(),
        ];
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getProjectDir() . "/app/config/config.yml");
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__, 2) . '/tmp';
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__, 2) . '/tmp';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
