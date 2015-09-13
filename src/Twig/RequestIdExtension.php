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

namespace Chrisguitarguy\RequestId\Twig;

use Chrisguitarguy\RequestId\RequestIdStorage;

/**
 * Add the request ID to twig as a function.
 *
 * @since   1.0
 */
final class RequestIdExtension extends \Twig_Extension
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
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('request_id', [$this->idStorage, 'getRequestId']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chirsguitarguy_request_id';
    }
}
