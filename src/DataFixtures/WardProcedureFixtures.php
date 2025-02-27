<?php

namespace App\DataFixtures;

use App\Entity\Procedure;
use App\Entity\Ward;
use App\Entity\WardProcedure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WardProcedureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $totalProcedures = 100;

        for ($wardIndex = 1; $wardIndex <= 100; $wardIndex++) {
            $ward = $this->getReference('ward_' . $wardIndex, Ward::class);

            $numProcedures = $faker->numberBetween(1, 10);

            $selectedProcedureIds = $faker->randomElements(range(1, $totalProcedures), $numProcedures);

            sort($selectedProcedureIds);

            $sequence = 1;
            foreach ($selectedProcedureIds as $procedureId) {
                $procedure = $this->getReference('procedure_' . $procedureId , Procedure::class);

                $wardProcedure = new WardProcedure();
                $wardProcedure->setWard($ward);
                $wardProcedure->setProcedure($procedure);

                $wardProcedure->setSequence($sequence);

                $manager->persist($wardProcedure);
                $this->addReference('wardProcedure_' . $wardIndex . '_' . $sequence, $wardProcedure);
                $sequence++;
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WardFixtures::class,
            ProcedureFixtures::class,
        ];
    }
}