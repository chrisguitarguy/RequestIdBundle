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

class HttpTest extends TestCase
{
    public function testRequestThatAlreadyHasARequestIdDoesNotReplaceIt()
    {
        $client = $this->createClient();

        $client->request('GET', '/', [], [], [
            'HTTP_REQUEST_ID'   => 'testId',
        ]);
        $resp = $client->getResponse();

        $this->assertEquals('testId', $resp->headers->get('Request-Id'));
        $this->assertEquals('testId', $client->getContainer()->get('chrisguitarguy.requestid.storage')->getRequestId());
    }

    public function testAlreadySetRequestIdUsesValueFromStorage()
    {
        $client = $this->createClient();
        $client->getContainer()->get('chrisguitarguy.requestid.storage')->setRequestId('abc123');

        $client->request('GET', '/');
        $resp = $client->getResponse();
        $req = $client->getRequest();

        $this->assertEquals('abc123', $resp->headers->get('Request-Id'));
        $this->assertEquals('abc123', $req->headers->get('Request-Id'));
    }

    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse()
    {
        $client = $this->createClient();

        $client->request('GET', '/');
        $resp = $client->getResponse();
        $req = $client->getRequest();

        $id = $client->getContainer()->get('chrisguitarguy.requestid.storage')->getRequestId();
        $this->assertNotEmpty($id);
        $this->assertEquals($id, $resp->headers->get('Request-Id'));
        $this->assertEquals($id, $req->headers->get('Request-Id'));
    }
}
