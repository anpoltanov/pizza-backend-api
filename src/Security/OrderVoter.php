<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class OrderVoter
 * @package App\Security
 */
class OrderVoter extends Voter
{
    const INDEX = 'order.index';
    const GET = 'order.get';
    const EDIT = 'order.edit';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (in_array($attribute, [self::GET, self::EDIT]) && $subject instanceof Order) {
            return true;
        }

        if (in_array($attribute, [self::INDEX,]) && is_int($subject)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $attribute
     * @param Order|int $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::GET:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::INDEX:
                return $this->canIndex($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     */
    final protected function canView(Order $order, User $user)
    {
        return $order->getUser()->getId() === $user->getId();
    }

    /**
     * @param Order $order
     * @param User $user
     * @return bool
     */
    final protected function canEdit(Order $order, User $user)
    {
        return $order->getUser()->getId() === $user->getId() && $order->getStatus() === Order::STATUS_CART;
    }

    /**
     * @param int $userId
     * @param User $user
     * @return bool
     */
    final protected function canIndex(int $userId, User $user)
    {
        return $userId === $user->getId();
    }
}