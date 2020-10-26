<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 26.10.2020
 */
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\IncompleteTestError;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListActionWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/list');
        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testListAction(): void
    {
        $client = static::createClient();

        $testUser = $this->getEnabledUser();
        if (!$testUser) {
            throw new IncompleteTestError(
                'There is no user enabled for logged in. Please make sure to provide this data, before running the test again.'
            );
        }
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/user/list');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('User list');
        self::assertSelectorTextContains('h3', 'List of application users');
        self::assertLessThanOrEqual(10, $crawler->filter('div.user-container')->count());
        self::assertEquals(1, $crawler->filter('div.navigation')->count());
    }

    private function getEnabledUser(): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $qb = $userRepository->createQueryBuilder('u');
        $result = $qb->select()
            ->where($qb->expr()->eq('u.enabled', 1))
            ->orderBy('u.id')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return reset($result);
    }
}
