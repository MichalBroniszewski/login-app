<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 26.10.2020
 */
declare(strict_types=1);

namespace Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListActionWhenAllowed(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->find(1);

        $client->loginUser($testUser);

        $client->request('GET', '/user/list');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h3', 'List of application users');
    }
}
