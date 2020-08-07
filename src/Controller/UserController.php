<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Security\UserVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @method User getUser()
 */
class UserController extends AbstractEntityController
{
    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     *
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
     * @return array
     * @throws \Exception
     *
     * @Route("/user", name="user_get_current", methods = {"GET"})
     */
    public function getCurrentAction(Request $request): array
    {
        $this->denyAccessUnlessGranted(User::ROLE_REGISTERED_USER);
        $entity = $this->getUser();
        return [
            'data' => $this->entityHydrator->extract($entity),
        ];
    }
}