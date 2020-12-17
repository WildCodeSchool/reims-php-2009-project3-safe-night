<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $users = new User();
            $users->setFirstName($faker->firstName());
            $users->setLastName($faker->lastName());
            $users->setBirthday($faker->dateTime());
            $users->setEmail($faker->freeEmail());
            $users->setPhoneNumber($faker->phoneNumber());
            $users->setAddress($faker->address());
            //$users->setAvatar($faker->image($width = 200, $height = 200, 'cats', $fullPath = false));
            $manager->persist($users);
            $manager->flush();
        }
    }
}
