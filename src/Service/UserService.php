<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $router
    ) {}

    public function registerUser(User $user, string $plaintextPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->sendVerificationEmail($user);
    }

    private function sendVerificationEmail(User $user): void
    {
        $verificationUrl = $this->router->generate('app_verify_email',            
            ['id' => $user->getId()],        
            UrlGeneratorInterface::ABSOLUTE_URL 
        );

        $email = (new Email())
            ->from('no-reply@vetpharmacy.local')
            ->to($user->getEmail())
            ->subject('Подтверждение регистрации')
            ->html(sprintf(
                '<p>Спасибо за регистрацию!</p><p>Для подтверждения аккаунта, пожалуйста, нажмите на ссылку: <a href="%s">Подтвердить email</a></p>',
                $verificationUrl
            ));

        $this->mailer->send($email);
    }
}