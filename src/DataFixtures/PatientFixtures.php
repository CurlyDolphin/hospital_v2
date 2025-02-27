<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Enum\GenderEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PatientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $genders = ['male', 'female'];

        for ($i = 1; $i <= 1000; ++$i) {
            $patient = new Patient();

            $genderValue = $faker->randomElement($genders);

            if ('male' === $genderValue) {
                $firstName = $faker->firstNameMale;
            } else {
                $firstName = $faker->firstNameFemale;
            }

            $lastName = $faker->lastName;

            $patient
                ->setName($firstName)
                ->setLastName($lastName)
                ->setGender(GenderEnum::from($genderValue))
                ->setIdentified(true)
                ->setBirthday($faker->dateTimeBetween('-90 years', '-1 years'))
                ->setCardNumber($faker->unique()->numberBetween(1, 10000));

            $manager->persist($patient);

            $this->addReference('patient_' . $i, $patient);
        }

        $manager->flush();
    }
}
