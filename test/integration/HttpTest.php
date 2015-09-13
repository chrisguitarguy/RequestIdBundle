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

        $this->assertTrue($resp->headers->has('Request-Id'));
        $this->assertEquals('testId', $resp->headers->get('Request-Id'));
    }

    public function testRequestWithOutRequestIdCreatesOnAndPassesThroughTheResponse()
    {
        $client = $this->createClient();

        $client->request('GET', '/');
        $resp = $client->getResponse();

        $this->assertTrue($resp->headers->has('Request-Id'));
        $this->assertNotEmpty($resp->headers->get('Request-Id'));
    }
}
