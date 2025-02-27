<?php

namespace App\DataFixtures;

use App\Entity\Hospitalization;
use App\Entity\Patient;
use App\Entity\Ward;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HospitalizationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 1000; ++$i) {
            $hospitalization = new Hospitalization();

            $patient = $this->getReference('patient_'.$i, Patient::class);

            $wardRef = (($i - 1) % 100) + 1;
            $ward = $this->getReference('ward_'.$wardRef, Ward::class);

            $hospitalization->setPatient($patient);
            $hospitalization->setWard($ward);

            if ($faker->boolean(40)) {
                $dischargeDate = $faker->dateTimeBetween('now', '+1 day');
                $hospitalization->setDischargeDate($dischargeDate);
            }

            $manager->persist($hospitalization);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixtures::class,
            WardFixtures::class,
        ];
    }
}
