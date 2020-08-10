<?php

declare(strict_types=1);

namespace App\Hydrator;

/**
 * Interface HydratorInterface
 * @package App\Hydrator
 */
interface HydratorInterface
{
    /**
     * @param object $object
     * @return array
     */
    public function extract(object $object);

    /**
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, object $object): object;
}