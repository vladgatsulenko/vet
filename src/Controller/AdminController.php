<?php

namespace App\Controller;

use App\Dto\AdminUsersQuery;
use App\Repository\UserRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(
        UserRepository $userRepository,
        Paginator $paginator,
        ValidatorInterface $validator,
        #[MapQueryString] AdminUsersQuery $q
    ): Response {
        $errors = $validator->validate($q);
        if (count($errors) > 0) {
            $message = (string) $errors;
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, $message);
        }

        $page = max(1, $q->page);
        $limit = min(100, max(1, $q->limit));

        $total = $userRepository->countAllUsers();

        $pagination = $paginator->paginate($total, $page, $limit, ['max_visible' => 7]);

        $users = $userRepository->findUsersPaginated($pagination['currentPage'], $pagination['limit']);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
        ]);
    }
}
