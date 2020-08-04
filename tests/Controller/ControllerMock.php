<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\AbstractEntityController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ControllerMock
 * @package App\Test\Controller
 */
class ControllerMock extends AbstractEntityController
{
    /**
     * ControllerMock constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $entityClass
     * @return ControllerMock
     */
    public function setEntityClass(string $entityClass): ControllerMock
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityRepository(): object
    {
        return parent::getEntityRepository();
    }

    public function getIdFromRequest(Request $request): int
    {
        return parent::getIdFromRequest($request);
    }


}