<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Strategy\JsonRequestStrategy;
use App\Controller\Strategy\RequestStrategyInterface;
use App\Entity\User;
use App\Hydrator\AbstractHydrator;
use App\Security\UserVoter;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @method User getUser()
 */
class UserController extends AbstractEntityController
{
    /** @var RequestStrategyInterface */
    protected $requestStrategy;

    /**
     * OrderController constructor.
     * @param AbstractHydrator $hydrator
     */
    public function __construct(AbstractHydrator $hydrator)
    {
        $this->entityClass = User::class;
        $this->entityHydrator = $hydrator;
        $this->requestStrategy = new JsonRequestStrategy();
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * @Route("/users/{id}", name="user_get", requirements = {"id" = "\d+"}, methods = {"GET"})
     */
    public function getAction(Request $request): array
    {
        $this->denyAccessUnlessGranted(User::ROLE_REGISTERED_USER);
        $entity = $this->getEntityById($this->getIdFromRequest($request));
        $this->denyAccessUnlessGranted(UserVoter::GET, $entity);
        return [
            'data' => $this->entityHydrator->extract($entity),
        ];
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return array
     * @throws \Exception
     * @Route("/users", name="user_add", methods = {"POST"})
     */
    public function addAction(Request $request, UserService $userService): array
    {
        $data = $this->requestStrategy->unserializeRequestContent($request);
        $entity = new $this->entityClass();
        $this->entityHydrator->hydrate($data, $entity);
        $userService->save($entity);

        return [
            'status_code' => Response::HTTP_CREATED,
            'data' => [
                'id' => $entity->getId(),
            ],
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * @Route("/user", name="user_get_current", methods = {"GET"})
     */
    public function getCurrentAction(Request $request): array
    {
//        $this->denyAccessUnlessGranted(User::ROLE_REGISTERED_USER);
        $entity = $this->getUser();
        if (empty($entity)) {
            return ['data' => null];
        }
        return [
            'data' => $this->entityHydrator->extract($entity),
        ];
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return array
     * @throws \Exception
     * @Route("/user", name="user_add_anonymous", methods = {"POST"})
     */
    public function addAnonymousAction(Request $request, UserService $userService): array
    {
        $entity = new User();
        $entity->setAuthToken(md5(random_bytes(20) . $entity->getSalt()));
        $entity->setAuthTokenCreatedDateTime(new \DateTime());
        $entity->setRoles([User::ROLE_ANONYMOUS_USER]);
        $userService->save($entity);
        return [
            'data' => $this->entityHydrator->extract($entity),
        ];
    }
}