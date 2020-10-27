<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 26.10.2020
 */
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends TestCase
{
    private UserManager $testSubject;

    /**
     * @var MockObject|UserPasswordEncoderInterface
     */
    private MockObject $passwordEncoder;

    /**
     * @var MockObject|EntityManagerInterface
     */
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->passwordEncoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->getMock();
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $this->testSubject = new UserManager(
            $this->passwordEncoder,
            $this->entityManager
        );
    }

    public function testSaveNewUser(): void
    {
        $user = $this->getUser();

        $this->passwordEncoder->expects(self::once())
            ->method('encodePassword');

        $this->entityManager->expects(self::once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->testSubject->saveNewUser($user);
    }

    public function testDisableUser(): void
    {
        $user = $this->getUser();

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->testSubject->saveNewUser($user);
    }

    private function getUser(): User
    {
        $user = new User();
        $user->setUsername('username');
        $user->setPlainPassword('secret_password');

        return $user;
    }
}
