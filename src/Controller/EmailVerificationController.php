<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController
{
    #[Route('/verify-email/{id}', name: 'app_verify_email')]
    public function verifyEmail(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден.');
        }

        $user->setVerified(true);
        $entityManager->flush();

        $this->addFlash('success', 'Email успешно подтверждён!');

        return $this->redirectToRoute('app_login');
    }
}