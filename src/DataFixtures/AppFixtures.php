<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $admin = new User();

        /** @var non-empty-string $adminEmail */
        $adminEmail = 'admin@example.test';
        $admin->setEmail($adminEmail);

        $admin->setVerified(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Adminpass12#'));
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        $count = 50;
        for ($i = 1; $i <= $count; $i++) {
            $user = new User();

            /** @var non-empty-string $email */
            $email = $faker->unique()->safeEmail();

            $user->setEmail($email);
            $user->setVerified($faker->boolean(80));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            if (!$user->isVerified() && $faker->boolean(30)) {
                $user->setVerificationToken(bin2hex(random_bytes(12)));
                $user->setVerificationTokenExpiresAt($faker->dateTimeBetween('-1 week', '+2 weeks'));
            }

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
