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

namespace Chrisguitarguy\RequestId\Twig;

use Chrisguitarguy\RequestId\SimpleIdStorage;
use Twig\Loader\ArrayLoader;
use Twig\Environment;

class RequestIdExtensionTest extends \Chrisguitarguy\RequestId\UnitTestCase
{
    const TEMPLATE = '{{ request_id() }}';

    private $storage, $env;

    public function testTheRequestIdFunctionReturnsTheRequestIdFromTheStorage()
    {
        $this->storage->setRequestId('abc123');

        $result = $this->env->render('test');

        $this->assertSame($result, 'abc123');
    }

    public function setUp()
    {
        $loader = new ArrayLoader([
            'test' => self::TEMPLATE,
        ]);
        $this->env = new Environment($loader);
        $this->storage = new SimpleIdStorage();
        $this->env->addExtension(new RequestIdExtension($this->storage));
    }
}
