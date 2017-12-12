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

use Symfony\Component\HttpFoundation\Request;

/**
 * Stores the identifiers for the request.
 *
 * @since   1.0
 */
interface RequestIdStorage
{
    /**
     * Get the identifier of the request.
     *
     * @return  string|null Null if the request does not have an identifier
     */
    public function getRequestId() : ?string;

    /**
     * Set the request ID.
     *
     * @param   string $id The ID to set
     */
    public function setRequestId(string $id) : void;
}
