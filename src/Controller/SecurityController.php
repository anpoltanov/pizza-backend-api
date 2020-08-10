<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserService $userService)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (empty($user)) {
            throw $this->createAccessDeniedException();
        }
        $user->setAuthToken(md5(random_bytes(20) . $user->getSalt()));
        $user->setAuthTokenCreatedDateTime(new \DateTime());
        $userService->save($user);

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'authToken' => $user->getAuthToken(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Logout is not possible due to system configuration');
    }
}