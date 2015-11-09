<?php

namespace AppBundle\User;

use AppBundle\Entity\User;
use AppBundle\Game\GameEvent;
use AppBundle\Game\GameEvents;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CreditsBalanceDebitor implements EventSubscriberInterface
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ObjectManager $entityManager
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function onGameStart(GameEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof UsernamePasswordToken) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        $user->debit($event->getRequiredAmountOfCredits());
        $this->entityManager->flush($user);
    }

    public static function getSubscribedEvents()
    {
        return [
            GameEvents::START => 'onGameStart',
        ];
    }
}
