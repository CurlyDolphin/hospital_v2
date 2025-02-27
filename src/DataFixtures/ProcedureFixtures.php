<?php

namespace App\DataFixtures;

use App\Entity\Procedure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProcedureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 100; $i++) {
            $procedure = new Procedure();

            $procedure->setName($faker->unique()->words(2, true));
            $procedure->setDescription($faker->paragraph());

            $manager->persist($procedure);

            $this->addReference('procedure_' . $i, $procedure);
        }

        $manager->flush();
    }
}