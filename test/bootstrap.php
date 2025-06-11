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

// https://github.com/symfony/symfony/issues/53812#issuecomment-1962740145
use Symfony\Component\ErrorHandler\ErrorHandler;
set_exception_handler([new ErrorHandler(), 'handleException']);
