<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
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
}
