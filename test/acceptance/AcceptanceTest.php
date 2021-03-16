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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AcceptanceTest extends WebTestCase
{
    public function testRequestThatAlreadyHasARequestIdDoesNotReplaceIt()
    {
        $requestId = 'testId';

        $client = $this->createClient();
        $client->request('GET', '/', [], [], [
            'HTTP_REQUEST_ID' => $requestId,
        ]);

        $response = $client->getResponse();

        $this->assertSuccessfulResponse($response);
        $this->assertEquals($requestId, $response->headers->get('Request-Id'));
        $this->assertEquals($requestId, $client->getContainer()->get(RequestIdStorage::class)->getRequestId());
        $this->assertLogsHaveRequestId($client, $requestId);

        $this->assertStringContainsString($requestId, $response->getContent());
    }

    public function testAlreadySetRequestIdUsesValueFromStorage()
    {
        $requestId = 'abc123';

        $client = $this->createClient();
        $client->getContainer()->get(RequestIdStorage::class)->setRequestId($requestId);
        $client->request('GET', '/');

        $response = $client->getResponse();
        $request = $client->getRequest();

        $this->assertSuccessfulResponse($response);
        $this->assertEquals($requestId, $response->headers->get('Request-Id'));
        $this->assertEquals($requestId, $request->headers->get('Request-Id'));
        $this->assertLogsHaveRequestId($client, $requestId);
        $this->assertStringContainsString($requestId, $response->getContent());
    }

    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $response = $client->getResponse();
        $request = $client->getRequest();

        $this->assertSuccessfulResponse($response);

        $id = $client->getContainer()->get(RequestIdStorage::class)->getRequestId();

        $this->assertNotEmpty($id);
        $this->assertEquals($id, $response->headers->get('Request-Id'));
        $this->assertEquals($id, $request->headers->get('Request-Id'));
        $this->assertLogsHaveRequestId($client, $id);
        $this->assertStringContainsString($id, $response->getContent());
    }

    public static function publicServices()
    {
        return [
            [RequestIdStorage::class],
            [RequestIdGenerator::class],
        ];
    }

    /**
     * @dataProvider publicServices
     */
    public function testExpectedServicesArePubliclyAvaiableFromTheContainer(string $class)
    {
        $client = $this->createClient();
        $service = $client->getContainer()->get($class);

        $this->assertInstanceOf($class, $service);
    }

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    private function getLogs($client)
    {
        return $client->getContainer()->get('log.memory_handler')->getLogs();
    }

    private function assertLogsHaveRequestId($client, $id)
    {
        foreach ($this->getLogs($client) as $msg) {
            $this->assertStringContainsString($id, $msg); // veri
        }
    }

    private function assertSuccessfulResponse($resp)
    {
        $this->assertInstanceOf(Response::class, $resp);
        $this->assertGreaterThanOrEqual(200, $resp->getStatusCode());
        $this->assertLessThan(300, $resp->getStatusCode());
    }
}
