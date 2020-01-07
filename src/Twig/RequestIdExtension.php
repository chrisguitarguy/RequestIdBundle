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

namespace Chrisguitarguy\RequestId\Twig;

use Chrisguitarguy\RequestId\RequestIdStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Add the request ID to twig as a function.
 *
 * @since   1.0
 */
final class RequestIdExtension extends AbstractExtension
{
    /**
     * @var RequestIdStorage
     */
    private $idStorage;

    public function __construct(RequestIdStorage $storage)
    {
        $this->idStorage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() : array
    {
        return [
            new TwigFunction('request_id', [$this->idStorage, 'getRequestId']),
        ];
    }
}
