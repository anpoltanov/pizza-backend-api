<?php

declare(strict_types=1);

namespace App\Controller;

use App\Hydrator\AbstractHydrator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractEntityController
 * @package App\Controller
 */
abstract class AbstractEntityController extends AbstractController
{
    /** @var string */
    protected $entityClass;
    /** @var AbstractHydrator */
    protected $entityHydrator;

    /**
     * @return \Doctrine\Persistence\ObjectRepository
     */
    protected function getEntityRepository(): object
    {
        return $this->getDoctrine()->getManager()->getRepository($this->entityClass);
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    protected function getIdFromRequest(Request $request): int
    {
        $id = $request->attributes->getInt('id', 0);
        if ($id === 0) {
            throw $this->createNotFoundException(sprintf("%s entity with id %d doesn`t exist", $this->entityClass, $id));
        }
        return $id;
    }

    /**
     * @param int $id
     * @return object
     */
    protected function getEntityById(int $id): object
    {
        $entity = $this->getEntityRepository()->find($id);
        if (!$entity instanceof $this->entityClass) {
            throw $this->createNotFoundException(sprintf('%s not found', $this->entityClass));
        }
        return $entity;
    }
}