<?php

declare(strict_types=1);

namespace App\Serializer;

/**
 * Interface SerializerInterface
 * @package Component\Serializer
 */
interface SerializerInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function serialize(array $data): string;
}