<?php

declare(strict_types=1);

namespace App\Hydrator;

/**
 * Class OrderItemHydrator
 * @package App\Hydrator
 */
class OrderItemHydrator extends AbstractHydrator
{
    /** @var string[] */
    protected $strategy = [
        'product' => AbstractHydrator::class,
    ];

    /**
     * @param array $data
     * @param object $object
     * @return object
     * @throws \ReflectionException
     */
    public function hydrate(array $data, object $object): object
    {
        $data['product'] = $data['product']['id'];
        return parent::hydrate($data, $object);
    }
}