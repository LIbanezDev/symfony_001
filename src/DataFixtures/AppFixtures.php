<?php

namespace App\DataFixtures;

use App\Entity\Clinic;
use App\Entity\Pet;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_ES');
        $user = new User();
        $user->setName($faker->name);
        $user->setEmail($faker->email);
        $user->setBornDate(\DateTime::createFromFormat('d-m-Y', '18-01-2001'));
        $password = $this->hasher->hashPassword($user, '123456');
        $user->setPassword($password);

        $clinic = new Clinic();
        $clinic->setName($faker->name);
        $clinic->setAvaliable($faker->boolean(80));
        $clinic->setLocation($faker->address);

        $clinic->addUser($user);

        for ($i = 0; $i < 10; $i++) {
            $pet = new Pet();
            $pet->setName($faker->name);
            $pet->setOwner($user);
            $pet->setClinic($clinic);
            $manager->persist($pet);
        }

        $manager->persist($user);
        $manager->persist($clinic);
        $manager->flush();
    }
}
