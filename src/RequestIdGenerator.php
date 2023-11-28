<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId;

/**
 * Generates new (hopefully) unique request ID's for incoming requests if they
 * lack an ID.
 */
interface RequestIdGenerator
{
    /**
     * Create a new request ID.
     */
    public function generate(): string;
}
