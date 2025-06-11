<?php
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

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AcceptanceTest extends WebTestCase
{
    public function testRequestThatAlreadyHasARequestIdDoesNotReplaceIt() : void
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/', [], [], [
            'HTTP_REQUEST_ID'   => 'testId',
        ]);
        $resp = $client->getResponse();

        $this->assertSuccessfulResponse($resp);
        $this->assertEquals('testId', $resp->headers->get('Request-Id'));
        $this->assertEquals('testId', $client->getContainer()->get(RequestIdStorage::class)->getRequestId());
        $this->assertLogsHaveRequestId($client, 'testId');
        $this->assertGreaterThan(
            0,
            $crawler->filter('h1:contains("testId")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    public function testAlreadySetRequestIdUsesValueFromStorage() : void
    {
        $client = $this->createClient();
        $client->getContainer()->get(RequestIdStorage::class)->setRequestId('abc123');

        $crawler = $client->request('GET', '/');
        $resp = $client->getResponse();
        $req = $client->getRequest();

        $this->assertSuccessfulResponse($resp);
        $this->assertEquals('abc123', $resp->headers->get('Request-Id'));
        $this->assertEquals('abc123', $req->headers->get('Request-Id'));
        $this->assertLogsHaveRequestId($client, 'abc123');
        $this->assertGreaterThan(
            0,
            $crawler->filter('h1:contains("abc123")')->count(),
            'should have the request ID in the response HTML'
        );
    }

    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse() : void
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');
        $resp = $client->getResponse();
        $req = $client->getRequest();

        $this->assertSuccessfulResponse($resp);
        $id = $client->getContainer()->get(RequestIdStorage::class)->getRequestId();
        $this->assertNotEmpty($id);
        $this->assertEquals($id, $resp->headers->get('Request-Id'));
        $this->assertEquals($id, $req->headers->get('Request-Id'));
        $this->assertLogsHaveRequestId($client, $id);
        $this->assertGreaterThan(
            0,
            $crawler->filter(sprintf('h1:contains("%s")', $id))->count(),
            'should have the request ID in the response HTML'
        );
    }

    /**
     * @return array<string[]>
     */
    public static function publicServices() : array
    {
        return [
            [RequestIdStorage::class],
            [RequestIdGenerator::class],
        ];
    }

    #[DataProvider('publicServices')]
    public function testExpectedServicesArePubliclyAvaiableFromTheContainer(string $class) : void
    {
        $client = $this->createClient();

        $service = $client->getContainer()->get($class);

        $this->assertInstanceOf($class, $service);
    }

    protected static function getKernelClass() : string
    {
        return TestKernel::class;
    }

    /**
     * @return string[]
     */
    private function getLogs($client) : array
    {
        return $client->getContainer()->get('log.memory_handler')->getLogs();
    }

    private function assertLogsHaveRequestId($client, $id) : void
    {
        foreach ($this->getLogs($client) as $msg) {
            $this->assertStringContainsString($id, $msg);
        }
    }

    private function assertSuccessfulResponse($resp) : void
    {
        $this->assertInstanceOf(Response::class, $resp);
        $this->assertGreaterThanOrEqual(200, $resp->getStatusCode());
        $this->assertLessThan(300, $resp->getStatusCode());
    }
}
