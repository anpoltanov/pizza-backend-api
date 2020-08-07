<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Strategy\JsonRequestStrategy;
use App\Controller\Strategy\RequestStrategyInterface;
use App\Entity\Order;
use App\Entity\User;
use App\Hydrator\AbstractHydrator;
use App\Security\OrderVoter;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 * @method User getUser()
 * @Route("/users/{user_id}/orders", requirements = {"user_id" = "\d+"})
 */
class OrderController extends AbstractEntityController
{
    /** @var RequestStrategyInterface */
    protected $requestStrategy;

    /**
     * OrderController constructor.
     * @param AbstractHydrator $hydrator
     */
    public function __construct(AbstractHydrator $hydrator)
    {
        $this->entityClass = Order::class;
        $this->entityHydrator = $hydrator;
        $this->requestStrategy = new JsonRequestStrategy();
    }

    /**
     * @param Request $request
     * @return array
     * @Route("", name="orders_index", methods = {"GET"})
     */
    public function indexAction(Request $request)
    {
        $userId = $this->getUserIdFromRequest($request);
        $this->denyAccessUnlessGranted(OrderVoter::INDEX, $userId);
        $entities = $this->getEntityRepository()->findAllBy(['user' => $userId]);
        $result = [
            'data' => [],
        ];
        foreach ($entities as $entity) {
            $result['data'][] = $this->entityHydrator->extract($entity);
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \ReflectionException
     * @Route("/{id}", name="orders_get", requirements = {"id" = "\d+"}, methods = {"GET"})
     */
    public function getAction(Request $request): array
    {
        $entity = $this->getEntityById($this->getIdFromRequest($request));
        $this->denyAccessUnlessGranted(OrderVoter::GET, $entity);
        return [
            'data' => $this->entityHydrator->extract($entity),
        ];
    }

    /**
     * @param Request $request
     * @param OrderService $orderService
     * @return array
     * @throws \Exception
     * @Route("", name="organization_add", methods = {"POST"})
     */
    public function addAction(Request $request, OrderService $orderService): array
    {
        $data = $this->requestStrategy->unserializeRequestContent($request);
        $entity = new $this->entityClass();
        $this->entityHydrator->hydrate($data, $entity);
        $orderService->save($entity);

        return [
            'status_code' => Response::HTTP_CREATED,
            'data' => [
                'id' => $entity->getId(),
            ],
        ];
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    protected function getUserIdFromRequest(Request $request): int
    {
        $id = $request->attributes->getInt('user_id', 0);
        if ($id === 0) {
            throw $this->createNotFoundException(sprintf("%s entity with id %d doesn`t exist", User::class, $id));
        }
        return $id;
    }
}