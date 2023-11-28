<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Monolog;

use DR\SymfonyRequestId\RequestIdStorage;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Adds the request ID to the Monolog record's `extra` key, so it can be used in formatters, etc.
 * @internal
 */
final class RequestIdProcessor implements ProcessorInterface
{
    public function __construct(private readonly RequestIdStorage $storage)
    {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $id = $this->storage->getRequestId();
        if ($id !== null) {
            $record->extra['request_id'] = $id;
        }

        return $record;
    }
}
