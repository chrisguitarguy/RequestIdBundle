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

use Monolog\LogRecord;
use function count;
use Countable;
use Monolog\Handler\AbstractProcessingHandler;

final class MemoryHandler extends AbstractProcessingHandler implements Countable
{
    private $logs = [];

    /**
     * {@inheritdoc}
     */
    protected function write($record): void
    {
        if ($record instanceof LogRecord) {
            $this->logs[] = (string) $record->formatted;
        } else {
            $this->logs[] = (string) $record['formatted'];
        }
    }

    public function count() : int
    {
        return count($this->logs);
    }

    /**
     * @return string[]
     */
    public function getLogs() : array
    {
        return $this->logs;
    }
}
