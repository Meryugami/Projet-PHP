<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'azerty'));
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $this->addReference('user_'.$i, $user);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setLastname('admin');
        $admin->setFirstname('admin');
        $admin->setPassword('$2y$10$hQKgx829b7teQzmcWKayn.a.xx2Wq1SGsWYrpprJGNKbXCSylWCNi');
        $manager->persist($admin);

        $manager->flush();
    }
}
