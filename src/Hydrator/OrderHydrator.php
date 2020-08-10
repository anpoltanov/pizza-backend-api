<?php

declare(strict_types=1);

namespace App\Hydrator;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Hydrator\Strategy\OrderItemStrategy;

/**
 * Class OrderHydrator
 * @package App\Hydrator
 */
class OrderHydrator extends AbstractHydrator
{
    /** @var string[] */
    protected $strategy = [
        'orderItems' => OrderItemStrategy::class,
    ];

    /**
     * @param array $data
     * @param object $object
     * @return Order object
     * @throws \ReflectionException
     */
    public function hydrate(array $data, object $object): object
    {
        if (!empty($data['orderItems'])) {
            $orderItems = $data['orderItems'];
            unset($data['orderItems']);
        }
        /** @var Order $object */
        $object = parent::hydrate($data, $object);
        if (!empty($orderItems)) {
            foreach ($orderItems as $orderItemData) {
                /** @var OrderItem $orderItemObject */
                $orderItemObject = parent::hydrate([
                    'product' => $orderItemData['product']['id'],
                    'amount' => $orderItemData['amount'],
                ], new OrderItem());
                $orderItemObject->setOrder($object);
                $object->addOrderItem($orderItemObject);
            }
        }
        return $object;
    }
}