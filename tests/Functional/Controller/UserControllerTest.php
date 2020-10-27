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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testListActionWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost/user/list');
        self::assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('http://localhost/login');
    }

    public function testListAction(): void
    {
        $client = static::createClient();
        $testUser = $this->getEnabledUser();
        if (!$testUser) {
            self::markTestSkipped('There is no user enabled for logged in. Test skipped.');
        }
        $client->loginUser($testUser);
        $crawler = $client->request('GET', 'http://localhost/user/list');
        self::assertResponseIsSuccessful();

        $this->checkUserList($crawler);
        $this->checkPagination($crawler, $client);
    }

    private function checkUserList(Crawler $crawler): void
    {
        self::assertPageTitleContains('User list');
        self::assertSelectorTextContains('h3', 'List of application users');
        self::assertLessThanOrEqual(10, $crawler->filter('div.user-container')->count());
    }

    private function checkPagination(Crawler $crawler, KernelBrowser $client): void
    {
        self::assertEquals(1, $crawler->filter('div.navigation')->count());

        if ($crawler->filter('a.page-link')->count() > 1) {
            $link = $crawler
                ->filter('a.page-link')
                ->eq(0)
                ->link();

            $client->click($link);
            self::assertResponseIsSuccessful();
            self::assertEquals('http://localhost/user/list/2', $client->getRequest()->getUri());
        }
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
