<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SuperAdminVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        // TODO: Implement supportsAttribute() method.
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();
        if ($user instanceof User && $user->isSuperAdmin()) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
