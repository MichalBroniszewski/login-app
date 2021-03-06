<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 25.10.2020
 */
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const NUMBER_OF_USERS = 35;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        for ($iterator = 1; $iterator <= self::NUMBER_OF_USERS; $iterator++) {
            $user = new User();
            $user->setUsername('user' . $iterator);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'pass123'
                )
            );
            $user->setRoles([User::ROLE_USER]);
            $user->setEnabled(true);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
