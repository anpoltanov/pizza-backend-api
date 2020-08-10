<?php

declare(strict_types=1);

namespace App\Serializer;

/**
 * Class JsonSerializer
 * @package Component\Serializer
 */
class JsonSerializer implements SerializerInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function serialize(?array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}