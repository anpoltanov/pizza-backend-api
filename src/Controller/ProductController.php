<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Hydrator\AbstractHydrator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/products")
 */
class ProductController extends AbstractEntityController
{
    /**
     * ProductController constructor.
     * @param AbstractHydrator $hydrator
     */
    public function __construct(AbstractHydrator $hydrator)
    {
        $this->entityClass = Product::class;
        $this->entityHydrator = $hydrator;
    }

    /**
     * @param Request $request
     * @return array
     * @Route("", name="product_index", methods = {"GET"})
     */
    public function indexAction(Request $request)
    {
        $entities = $this->getEntityRepository()->findAll();
        $result = [
            'ttl' => 600,
            'data' => [],
        ];
        foreach ($entities as $entity) {
            $result['data'][] = $this->entityHydrator->extract($entity);
        }
        return $result;
    }
}