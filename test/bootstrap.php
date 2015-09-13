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

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('Chrisguitarguy\\RequestId\\', __DIR__.'/integration/');
$loader->addPsr4('Chrisguitarguy\\RequestId\\', __DIR__.'/unit/');
require __DIR__.'/integration/app/TestKernel.php';
