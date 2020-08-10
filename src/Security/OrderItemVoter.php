<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\OrderItem;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class OrderItemVoter
 * @package App\Security
 */
class OrderItemVoter extends Voter
{
    const ADD = 'orderItem.add';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (in_array($attribute, [self::ADD,]) && $subject instanceof OrderItem) {
            return true;
        }

        return false;
    }

    /**
     * @param string $attribute
     * @param OrderItem $subject
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
            case self::ADD:
                return $this->canAdd($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param OrderItem $orderItem
     * @param User $user
     * @return bool
     */
    final protected function canAdd(OrderItem $orderItem, User $user)
    {
        return $orderItem->getOrder()->getUser()->getId() === $user->getId();
    }
}