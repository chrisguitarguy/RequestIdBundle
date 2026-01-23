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
use Monolog\LogRecord;

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
    private RequestIdStorage $idStorage;

    /**
     * @var string
     */
    private string $fieldName;

    public function __construct(RequestIdStorage $storage, string $fieldName = 'request_id')
    {
        $this->idStorage = $storage;
        $this->fieldName = $fieldName;
    }

    /**
     * @param array|LogRecord $record
     *
     * @return array|LogRecord
     */
    public function __invoke($record)
    {
        if ($id = $this->idStorage->getRequestId()) {
            if ($record instanceof LogRecord) {
                $record->extra[$this->fieldName] = $id;
            } else {
                $record['extra'][$this->fieldName] = $id;
            }
        }

        return $record;
    }
}
