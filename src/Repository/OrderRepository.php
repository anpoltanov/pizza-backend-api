<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class OrderRepository
 * @package App\Repository
 */
class OrderRepository extends EntityRepository
{
    /**
     * @param int $userId
     * @return Order[]
     */
    public function getActualCartByUser(int $userId)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :userId')
            ->andWhere('o.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', Order::STATUS_CART)
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_OBJECT)
            ->execute();
    }
}