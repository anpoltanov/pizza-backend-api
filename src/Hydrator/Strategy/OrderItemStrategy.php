<?php

declare(strict_types=1);

namespace App\Hydrator\Strategy;

use App\Hydrator\AbstractHydrator;
use App\Hydrator\HydratorStrategyInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrderItemStrategy
 * @package App\Hydrator\Strategy
 */
class OrderItemStrategy implements HydratorStrategyInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var string[] */
    protected $strategy = [
        'product' => AbstractHydrator::class,
    ];

    /**
     * OrderItemStrategy constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $object
     * @return array|object
     * @throws \ReflectionException
     */
    public function extract(object $object)
    {
        if ($object instanceof Collection) {
            $data = [];
            foreach ($object as $item) {
                $data[] = (new AbstractHydrator($this->entityManager))->setStrategy($this->strategy)->extract($item);
            }
            return $data;
        }
        return $object;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, object $object): object
    {
        return $object;
    }
}