<?php

declare(strict_types=1);

namespace App\Listener\ResponseFactory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponseFactory
 * @package App\Listener\ResponseSerializer
 */
class JsonResponseFactory implements ResponseFactoryInterface
{
    /**
     * @param $json
     * @return Response
     */
    public static function create($json): Response
    {
        return JsonResponse::fromJsonString($json);
    }
}