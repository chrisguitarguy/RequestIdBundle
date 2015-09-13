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

/**
 * Generates new (hopefully) unique request ID's for incoming requests if they
 * lack an ID.
 *
 * @since   1.0
 */
interface RequestIdGenerator
{
    /**
     * Create a new request ID.
     *
     * @return  string
     */
    public function generate();
}
