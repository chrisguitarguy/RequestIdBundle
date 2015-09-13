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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}
