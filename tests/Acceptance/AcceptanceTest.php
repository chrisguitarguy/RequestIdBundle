<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Acceptance;

use DR\SymfonyRequestId\RequestIdGenerator;
use DR\SymfonyRequestId\RequestIdStorage;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AcceptanceTest extends WebTestCase
{
    public function testRequestThatAlreadyHasARequestIdDoesNotReplaceIt(): void
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/', [], [], [
            'HTTP_REQUEST_ID' => 'testId',
        ]);
        $resp    = $client->getResponse();

        static::assertSuccessfulResponse($resp);
        static::assertSame('testId', $resp->headers->get('Request-Id'));
        static::assertSame('testId', $client->getContainer()->get(RequestIdStorage::class)->getRequestId());
        static::assertLogsHaveRequestId($client, 'testId');
        static::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("testId")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    public function testAlreadySetRequestIdUsesValueFromStorage(): void
    {
        $client = $this->createClient();
        $client->getContainer()->get(RequestIdStorage::class)->setRequestId('abc123');

        $crawler = $client->request('GET', '/');
        $resp    = $client->getResponse();
        $req     = $client->getRequest();

        static::assertSuccessfulResponse($resp);
        static::assertSame('abc123', $resp->headers->get('Request-Id'));
        static::assertSame('abc123', $req->headers->get('Request-Id'));
        static::assertLogsHaveRequestId($client, 'abc123');
        static::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("abc123")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse(): void
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');
        $resp    = $client->getResponse();
        $req     = $client->getRequest();

        static::assertSuccessfulResponse($resp);
        $id = $client->getContainer()->get(RequestIdStorage::class)->getRequestId();
        static::assertNotEmpty($id);
        static::assertSame($id, $resp->headers->get('Request-Id'));
        static::assertSame($id, $req->headers->get('Request-Id'));
        static::assertLogsHaveRequestId($client, $id);
        static::assertGreaterThan(
            0,
            $crawler->filter(sprintf('h1:contains("%s")', $id))->count(),
            'should have the request ID in the response HTML'
        );
    }

    /**
     * @return array<string[]>
     */
    public static function publicServices(): array
    {
        return [
            [RequestIdStorage::class],
            [RequestIdGenerator::class],
        ];
    }

    #[DataProvider('publicServices')]
    public function testExpectedServicesArePubliclyAvailableFromTheContainer(string $class): void
    {
        $client = $this->createClient();

        $service = $client->getContainer()->get($class);

        static::assertInstanceOf($class, $service);
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    /**
     * @return string[]
     */
    private static function getLogs($client): array
    {
        return $client->getContainer()->get('log.memory_handler')->getLogs();
    }

    private static function assertLogsHaveRequestId($client, $id): void
    {
        foreach (self::getLogs($client) as $msg) {
            static::assertStringContainsString($id, $msg);
        }
    }

    private static function assertSuccessfulResponse($resp): void
    {
        static::assertInstanceOf(Response::class, $resp);
        static::assertGreaterThanOrEqual(200, $resp->getStatusCode());
        static::assertLessThan(300, $resp->getStatusCode());
    }
}
