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
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setBirthday($faker->dateTime());
            $user->setEmail($faker->freeEmail());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setAddress($faker->address());
            //$user->setAvatar($faker->image($width = 200, $height = 200, 'cats', $fullPath = false));
            $manager->persist($user);
            $manager->flush();
        }
    }
}
