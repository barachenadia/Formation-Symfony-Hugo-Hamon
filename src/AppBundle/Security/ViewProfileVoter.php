<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use AppBundle\Entity\User;

class ViewProfileVoter extends AbstractVoter
{
    protected function getSupportedClasses()
    {
        return [ User::class ];
    }

    protected function getSupportedAttributes()
    {
        return [ 'VIEW_PROFILE' ];
    }

    protected function isGranted($attribute, $profile, $user = null)
    {
        if (!$profile instanceof User || !$user instanceof User) {
            return false;
        }

        if ($profile->getUsername() === $user->getUsername()) {
            return true;
        }

        return $user->hasFriendshipRelationshipWith($profile);
    }
}
