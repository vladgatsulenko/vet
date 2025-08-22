<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(Request $request, UserRepository $userRepository): Response
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, (int) $request->query->get('limit', 10));

        $total = $userRepository->countAllUsers();

        $totalPages = (int) ceil($total / $limit);
        if ($totalPages < 1) {
            $totalPages = 1;
        }

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