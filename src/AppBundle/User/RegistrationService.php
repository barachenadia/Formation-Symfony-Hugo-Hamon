<?php

namespace AppBundle\User;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;

class RegistrationService
{
    private $saltGenerator;
    private $passwordEncoder;
    private $entityManager;

    public function __construct(
        SecureRandomInterface $saltGenerator,
        UserPasswordEncoderInterface $passwordEncoder,
        ObjectManager $entityManager
    )
    {
        $this->saltGenerator = $saltGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function register(User $user, $flush = true)
    {
        $user->addPermission('ROLE_PLAYER');
        $user->setSalt(sha1($this->saltGenerator->nextBytes(128)));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        if (!$user->getCreditsBalance()) {
            $user->credit(500);
        }

        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
