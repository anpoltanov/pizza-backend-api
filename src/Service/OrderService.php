<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrderService
 * @package App\Service
 */
class OrderService
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
     * @param Order $entity
     * @throws \Exception
     */
    public function save(Order $entity)
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

    /**
     * @param $userId
     * @return mixed
     */
    public function getCart($userId)
    {
        $repository = $this->em->getRepository(Order::class);
        $entity = $repository->getActualCartByUser($userId);
        if (is_array($entity)) {
            $entity = array_shift($entity);
        }
        return $entity;
    }
}