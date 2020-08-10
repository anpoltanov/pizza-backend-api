<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Strategy\JsonRequestStrategy;
use App\Controller\Strategy\RequestStrategyInterface;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Hydrator\OrderItemHydrator;
use App\Security\OrderItemVoter;
use App\Service\OrderItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderItemController
 * @package App\Controller
 * @method User getUser()
 * @Route("/users/{user_id}/orders/{order_id}/items", requirements = {"user_id" = "\d+", "order_id" = "\d+"})
 */
class OrderItemController extends AbstractEntityController
{
    /** @var RequestStrategyInterface */
    protected $requestStrategy;

    /**
     * OrderController constructor.
     * @param OrderItemHydrator $hydrator
     */
    public function __construct(OrderItemHydrator $hydrator)
    {
        $this->entityClass = OrderItem::class;
        $this->entityHydrator = $hydrator;
        $this->requestStrategy = new JsonRequestStrategy();
    }

    /**
     * @param Request $request
     * @param OrderItemService $orderItemService
     * @return array
     * @throws \Exception
     * @Route("", name="order_items_add", methods = {"POST"})
     */
    public function addAction(Request $request, OrderItemService $orderItemService): array
    {
        $this->denyAccessUnlessGranted(User::ROLE_USER);
        $data = $this->requestStrategy->unserializeRequestContent($request);
        $data['order'] = $this->getOrderIdFromRequest($request);
        /** @var OrderItem $entity */
        $entity = new $this->entityClass;
        $this->entityHydrator->hydrate($data, $entity);
        $this->denyAccessUnlessGranted(OrderItemVoter::ADD, $entity);
        $orderItemService->save($entity);

        return [
            'status_code' => Response::HTTP_CREATED,
            'data' => $this->entityHydrator->extract($entity),
        ];
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    protected function getOrderIdFromRequest(Request $request): int
    {
        $id = $request->attributes->getInt('order_id', 0);
        if ($id === 0) {
            throw $this->createNotFoundException(sprintf("%s entity with id %d doesn`t exist", Order::class, $id));
        }
        return $id;
    }
}