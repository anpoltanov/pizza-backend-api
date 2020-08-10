<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
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
     * @param User $entity
     * @return User
     * @throws \Exception
     */
    public function save(User $entity): User
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

        return $entity;
    }
}