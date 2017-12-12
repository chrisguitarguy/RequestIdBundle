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

namespace Chrisguitarguy\RequestId\Monolog;

use Chrisguitarguy\RequestId\RequestIdStorage;

/**
 * Adds the request ID to the Monolog record's `extra` key so it can be used
 * in formatters, etc.
 *
 * @since   1.0
 */
final class RequestIdProcessor
{
    /**
     * @var RequestIdStorage
     */
    private $idStorage;

    public function __construct(RequestIdStorage $storage)
    {
        $this->idStorage = $storage;
    }

    public function __invoke(array $record) : array
    {
        if ($id = $this->idStorage->getRequestId()) {
            $record['extra']['request_id'] = $id;
        }

        return $record;
    }
}
