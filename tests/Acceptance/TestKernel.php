<?php
declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Acceptance;

use DR\SymfonyRequestId\RequestIdBundle;
use DR\Utils\Assert;
use Exception;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles(): array
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
        return __DIR__ . '/app/tmp';
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/app/tmp';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function boot(): void
    {
        // clear up the cached files
        foreach (Assert::notFalse(glob(__DIR__ . '/app/tmp/*')) as $fn) {
            if ('.' !== basename($fn)[0]) {
                @unlink($fn);
            }
        }

        parent::boot();
    }
}
