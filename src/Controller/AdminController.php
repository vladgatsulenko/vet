<?php

namespace App\Controller;

use App\Dto\AdminUsersQuery;
use App\Repository\UserRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(
        UserRepository $userRepository,
        Paginator $paginator,
        #[MapQueryString] AdminUsersQuery $query
    ): Response {
        $total = $userRepository->countAllUsers();

        $pagination = $paginator->paginate($total, $query->page, $query->limit);

        $users = $userRepository->findPaginated($pagination->offset, $pagination->limit);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
        ]);
    }
}
