<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ItemService
 * @package App\Service\Order
 */
class OrderItemService
{
    /** @var EntityManager */
    protected $em;

    /**
     * OrderService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param OrderItem $entity
     * @throws \Exception
     */
    public function save(OrderItem $entity)
    {
        $this->em->beginTransaction();
        try {
            $this->em->persist($entity);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}