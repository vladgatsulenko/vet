<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid; 
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;
class UserService
{
    
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $router,
        private ParameterBagInterface $params,
        private \Twig\Environment $twig, 
        private TranslatorInterface $translator
    ) {}

    public function registerUser(User $user, string $plaintextPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);

        $token = Uuid::v4()->toRfc4122();
        $user->setVerificationToken($token);

        $expiresAt = new \DateTime('+24 hours');
        $user->setVerificationTokenExpiresAt($expiresAt);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->sendVerificationEmail($user);
    }

    private function sendVerificationEmail(User $user): void
    {
        $domain = $this->params->get('app.domain');
        $verificationUrl = $this->router->generate('app_verify_email',            
            [
             'id' => $user->getId(),
             'token' => $user->getVerificationToken()
            ],        
            UrlGeneratorInterface::ABSOLUTE_URL 
        );

        $email = (new TemplatedEmail())
            ->from('no-reply@' . $domain)
            ->to($user->getEmail())
            ->subject($this->translator->trans('emailVerificationPageTitle'))
            ->htmlTemplate('emails/verification.html.twig')
            ->context([
                'user' => $user,
                'verificationUrl' => $verificationUrl 
            ]);

        $this->mailer->send($email);
    }
}