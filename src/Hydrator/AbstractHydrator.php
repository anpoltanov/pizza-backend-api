<?php

declare(strict_types=1);

namespace App\Hydrator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractHydrator
 * @package App\Hydrator
 */
class AbstractHydrator implements HydratorStrategyInterface
{
    /** @var array */
    protected $excludeFields = [];
    /** @var EntityManager */
    protected $entityManager;
    /** @var array  */
    protected $strategy = [];

    /**
     * AbstractHydrator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $object
     * @return array
     * @throws \ReflectionException
     */
    public function extract(object $object)
    {
        $result = [];
        $reflection = new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if (in_array($name, $this->excludeFields)) {
                continue;
            }
            $getter = 'get' . strtoupper($name);
            if (!method_exists($object, $getter)) {
                $getter = 'is' . strtoupper($name);
            }
            if (method_exists($object, $getter)) {
                $value = $object->$getter();
                if (array_key_exists($name, $this->strategy)) {
                    $value = $this->extractByStrategy($value, $this->strategy[$name]);
                }
                if (is_object($value) && !$value instanceof Collection) {
                    if (method_exists($value, 'getId')) {
                        $value = $value->getId();
                    } elseif ($value instanceof \DateTime) {
                        $value = $value->format('Y-m-d H:i');
                    }
                }
                if ($value instanceof Collection) {
                    $data = [];
                    foreach ($value as $item) {
                        $data[] = $this->extract($item);
                    }
                    $value = $data;
                }
                $result[$property->getName()] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $value
     * @param array|string $strategy
     * @return array|void
     */
    private function extractByStrategy($value, $strategy)
    {
        if (!class_exists($strategy)) {
            throw new Exception\InvalidArgumentException('Strategy class doesnt exist');
        }
        $strategyInstance = new $strategy($this->entityManager);
        if (!$strategyInstance instanceof HydratorStrategyInterface) {
            throw new Exception\InvalidArgumentException('Cannot use provided strategy class');
        }
        return $strategyInstance->extract($value);
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     * @throws \ReflectionException
     */
    public function hydrate(array $data, object $object): object
    {
        $classMetadata = $this->entityManager->getClassMetadata(get_class($object));
        foreach ($data as $fieldName => $fieldValue) {
            $setter = 'set' . strtoupper($fieldName);
            if (!method_exists($object, $setter)) {
                continue;
            }
            if ($classMetadata->hasField($fieldName)) {
                $type = $classMetadata->getTypeOfField($fieldName);
                if ($type === 'datetime') {
                    $fieldValue = new \DateTime($fieldValue);
                }
            }
            if ($classMetadata->hasAssociation($fieldName)) {
                if (is_numeric($fieldValue)) {
                    $fieldValue = $this->getAssociationEntity($classMetadata, $fieldName, $fieldValue);
                }
                if (is_array($fieldValue)) {
                    foreach ($fieldValue as $key => $item) {
                        if (is_numeric($item)) {
                            $fieldValue[$key] = $this->getAssociationEntity($classMetadata, $fieldName, $item);
                            // @TODO set mapping side of relationship
                            $mappedBy = $classMetadata->getAssociationMappedByTargetField($fieldName);
                        }
                    }
                    if ($classMetadata->isCollectionValuedAssociation($fieldName)) {
                        $fieldValue = new ArrayCollection($fieldValue);
                    }
                }
            }
            $object->$setter($fieldValue);
        }
        return $object;
    }

    /**
     * @param $classMetadata
     * @param $fieldName
     * @param $id
     * @return object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function getAssociationEntity($classMetadata, $fieldName, $id)
    {
        $class = $classMetadata->getAssociationTargetClass($fieldName);
        $association = $this->entityManager->find($class, $id);
        if (empty($association)) {
            throw new Exception\InvalidArgumentException(sprintf('Association entity %s with id %d not found', $class, $id));
        }
        return $association;
    }

    /**
     * @param array $excludes
     */
    protected function addExcludeFields(array $excludes)
    {
        $this->excludeFields = array_merge($this->excludeFields, $excludes);
    }

    /**
     * @param array $strategy
     * @return $this
     */
    public function setStrategy(array $strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }
}