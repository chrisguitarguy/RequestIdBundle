<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId;

/**
 * Stores the identifiers for the request.
 */
interface RequestIdStorage
{
    /**
     * @return string|null Null if the request does not have an identifier
     */
    public function getRequestId(): ?string;

    public function setRequestId(?string $id): void;
}
