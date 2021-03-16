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

/**
 * And ID storage backed by a property, simple.
 *
 * @since   1.0
 */
final class SimpleIdStorage implements RequestIdStorage
{
    /**
     * @var string
     */
    private $requestId;

    /**
     * {@inheritdoc}
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestId(?string $id): void
    {
        $this->requestId = $id;
    }
}
