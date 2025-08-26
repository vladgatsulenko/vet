<?php

namespace App\Controller;

use App\Dto\AdminUsersQuery;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(
        UserRepository $userRepository,
        #[MapQueryString] AdminUsersQuery $q
    ): Response {
    
        $page = max(1, $q->page);
        $limit = min(100, max(1, $q->limit));

        $total = $userRepository->countAllUsers();
        $totalPages = max(1, (int) ceil($total / $limit));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $users = $userRepository->findUsersPaginated($page, $limit);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'currentPage' => $page,
            'limit' => $limit,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }
}
