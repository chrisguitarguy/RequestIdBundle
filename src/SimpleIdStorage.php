<?php
declare(strict_types=1);

namespace DR\SymfonyRequestId;

/**
 * And ID storage backed by a property, simple.
 */
final class SimpleIdStorage implements RequestIdStorage
{
    private ?string $requestId = null;

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function setRequestId(?string $id): void
    {
        $this->requestId = $id;
    }
}
