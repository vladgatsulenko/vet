<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController
{
    #[Route('/verify-email/{id}/{token}', name: 'app_verify_email')]
    public function verifyEmail(int $id, string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        if($user->getVerificationToken() !== $token){
            throw $this->createNotFoundException();
        }

        $now = new \DateTime();
        if ($user->getVerificationTokenExpiresAt() < $now) {
            throw $this->createNotFoundException('Verification token has expired.');
        }

        $user->setVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpiresAt(null);
        $entityManager->flush();

        $this->addFlash('success', 'Email успешно подтверждён!');

        return $this->redirectToRoute('app_login');
    }
}