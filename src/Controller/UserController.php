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
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
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
    ): Response
    {
        $users = $paginator->paginate(
            $repository->getUserListQuery(),
            $page,
            10
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
     * @param EntityManagerInterface $entityManager
     * @param int|null $page
     * @return RedirectResponse
     */
    public function disableUser(
        User $user,
        EntityManagerInterface $entityManager,
        ?int $page
    ): RedirectResponse {
        if ($this->getUser()->isEnabled()) {
            $user->setEnabled(false);
            $entityManager->flush();
            $this->addFlash('notice', 'User has been blocked.');
        } else {
            $this->addFlash('error', 'You are not allowed to perform this operation');
        }

        return $this->redirectToRoute('user_list', [
            'page' => $page
        ]);
    }
}
