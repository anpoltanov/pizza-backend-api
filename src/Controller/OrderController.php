<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Hydrator\AbstractHydrator;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 * @Route("/users/{id}/orders", requirements = {"id" = "\d+"})
 */
class OrderController extends AbstractEntityController
{
    /**
     * OrderController constructor.
     * @param AbstractHydrator $hydrator
     */
    public function __construct(AbstractHydrator $hydrator)
    {
        $this->entityClass = Order::class;
        $this->entityHydrator = $hydrator;
    }


}