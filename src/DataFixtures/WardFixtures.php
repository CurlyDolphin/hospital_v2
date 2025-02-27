<?php

namespace App\DataFixtures;

use App\Entity\Ward;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WardFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 100; $i++) {
            $ward = new Ward();

            $ward->setWardNumber($faker->unique()->numberBetween(1, 1000));

            $ward->setDescription($faker->sentence(10));
            $manager->persist($ward);


            $this->addReference('ward_' . $i, $ward);
        }

        $manager->flush();
    }
}