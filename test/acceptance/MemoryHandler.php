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

use Monolog\Handler\AbstractProcessingHandler;
use Psr\Log\AbstractLogger;

final class MemoryHandler extends AbstractProcessingHandler implements \Countable
{
    private $logs = [];

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->logs[] = (string) $record['formatted'];
    }

    public function count(): int
    {
        return count($this->logs);
    }

    public function getLogs()
    {
        return $this->logs;
    }
}
