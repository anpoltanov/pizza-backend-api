<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Security\UserVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @method User getUser()
 * @Route("/users", requirements = {"id" = "\d+"})
 */
class UserController extends AbstractEntityController
{
    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     *
     * @Route("/{id}", name="user_get", requirements = {"id" = "\d+"}, methods = {"GET"})
     */
    public function getAction(Request $request): array
    {
        $this->denyAccessUnlessGranted(User::ROLE_REGISTERED_USER);
        $entity = $this->getEntityById($this->getIdFromRequest($request));
        $this->denyAccessUnlessGranted(UserVoter::GET, $entity);
        return [
            'status_code' => Response::HTTP_OK,
            'data' => $this->entityHydrator->extract($entity),
        ];
    }
}