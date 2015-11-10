<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use AppBundle\Entity\User;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function findMostRecentUsers($max)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u.id, u.username')
            ->where('u.username NOT IN(:usernames)')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults($max)
            ->setParameter('usernames', [ 'admin', '_exit' ])
            ->getQuery()
        ;

        return $q->getArrayResult();
    }

    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->orWhere('u.emailAddress = :username')
            ->setParameter('username', $username)
            ->getQuery()
        ;

        $user = $q->getOneOrNullResult();
        if (!$user instanceof User) {
            throw new UsernameNotFoundException(sprintf(
                'Unable to retrieve AppBundle:User identified by "%s".',
                $username
            ));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        $username = $user->getUsername();

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf(
                'User (%s) of type "%s" is not supported by this provided.',
                $username,
                $class
            ));
        }

        return $this->loadUserByUsername($username);
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
