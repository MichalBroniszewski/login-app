<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 26.10.2020
 */
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private UserPasswordEncoderInterface $passwordEncoder;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function saveNewUser(User $user): void
    {
        $password = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );
        $user->setPassword($password);
        $user->setEnabled(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function disableUser(User $user): void
    {
        $user->setEnabled(false);
        $this->entityManager->flush();
    }
}
