<?php

declare(strict_types=1);

namespace App\Listener\ResponseFactory;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResponseFactoryInterface
 * @package App\Listener\ResponseFactory
 */
interface ResponseFactoryInterface
{
    /**
     * @param $data
     * @return Response
     */
    public static function create($data): Response;
}