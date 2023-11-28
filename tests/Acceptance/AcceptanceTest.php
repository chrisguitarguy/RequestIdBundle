<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Acceptance;

use DR\SymfonyRequestId\RequestIdGenerator;
use DR\SymfonyRequestId\RequestIdStorage;
use Exception;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversNothing]
class AcceptanceTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testRequestThatAlreadyHasARequestIdDoesNotReplaceIt(): void
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/', [], [], ['HTTP_REQUEST_ID' => 'testId']);
        static::assertResponseIsSuccessful();

        $response = $client->getResponse();
        static::assertSame('testId', $response->headers->get('Request-Id'));
        static::assertSame('testId', self::getService(RequestIdStorage::class)->getRequestId());
        self::assertLogsHaveRequestId('testId');
        static::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("testId")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    /**
     * @throws Exception
     */
    public function testAlreadySetRequestIdUsesValueFromStorage(): void
    {
        $client = self::createClient();
        self::getService(RequestIdStorage::class)->setRequestId('abc123');

        $crawler = $client->request('GET', '/');
        static::assertResponseIsSuccessful();
        static::assertSame('abc123', $client->getResponse()->headers->get('Request-Id'));
        static::assertSame('abc123', $client->getRequest()->headers->get('Request-Id'));
        self::assertLogsHaveRequestId('abc123');
        static::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("abc123")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    /**
     * @throws Exception
     */
    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse(): void
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/');
        static::assertResponseIsSuccessful();

        $id = self::getService(RequestIdStorage::class)->getRequestId();
        static::assertNotEmpty($id);
        static::assertSame($id, $client->getResponse()->headers->get('Request-Id'));
        static::assertSame($id, $client->getRequest()->headers->get('Request-Id'));
        self::assertLogsHaveRequestId($id);
        static::assertGreaterThan(
            0,
            $crawler->filter(sprintf('h1:contains("%s")', $id))->count(),
            'should have the request ID in the response HTML'
        );
    }

    /**
     * @param class-string $class
     *
     * @throws Exception
     */
    #[TestWith([RequestIdStorage::class])]
    #[TestWith([RequestIdGenerator::class])]
    public function testExpectedServicesArePubliclyAvailableFromTheContainer(string $class): void
    {
        /** @var object $service */
        $service = self::getContainer()->get($class);

        static::assertInstanceOf($class, $service);
    }

    /**
     * @throws Exception
     */
    private static function assertLogsHaveRequestId(string $id): void
    {
        /** @var string[] $logs */
        $logs = self::getService(MemoryHandler::class, 'log.memory_handler')->getLogs();
        foreach ($logs as $message) {
            static::assertStringContainsString($id, $message);
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return T
     * @throws Exception
     */
    private static function getService(string $class, string $id = null): object
    {
        $service = self::getContainer()->get($id ?? $class);
        static::assertInstanceOf($class, $service);

        return $service;
    }
}
