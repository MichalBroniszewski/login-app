<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 25.10.2020
 */
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    private const USERS_PER_PAGE_PAGINATION_LIMIT = 10;

    /**
     * @Route("/list/{page}", name="user_list", defaults={"page"=1})
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @param int|null $page
     * @return Response
     */
    public function list(
        UserRepository $repository,
        PaginatorInterface $paginator,
        ?int $page
    ): Response {
        $users = $paginator->paginate(
            $repository->getUserListQuery(),
            $page,
            self::USERS_PER_PAGE_PAGINATION_LIMIT
        );

        return $this->render(
            'user/list.html.twig', [
                'users' => $users,
                'page' => $page
            ]
        );
    }

    /**
     * @Route("/disable/{id}/{page}", name="disable_user", defaults={"page"=null})
     * @param User $user
     * @param UserManager $userManager
     * @param int|null $page
     * @return RedirectResponse
     */
    public function disableUser(
        User $user,
        UserManager $userManager,
        ?int $page
    ): RedirectResponse {
        if (!$user->isEnabled()) {
            throw new BadRequestHttpException('User is already disabled.');
        }

        $userManager->disableUser($user);
        if ($this->isSelfDisable($user)) {
            return $this->redirectToRoute('security_logout');
        }

        $this->addFlash('success', 'User has been disabled.');

        return $this->redirectToRoute('user_list', [
            'page' => $page
        ]);
    }

    private function isSelfDisable(User $user): bool
    {
        return $this->getUser()->getUsername() === $user->getUsername();
    }
}
