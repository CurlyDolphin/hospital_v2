<?php

namespace App\Dto\Patient;

use App\Validator\IdentifiedBirthday\IdentifiedBirthday;
use Symfony\Component\Validator\Constraints as Assert;

#[IdentifiedBirthday]
class IdentifyPatientDto extends BasePatientDto
{
    public function __construct(

        public string $name,

        public string $lastName,

        #[Assert\NotBlank(message: 'Дата рождения должна быть задана')]
        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: 'Дата рождения не может быть позже текущей даты'
        )]
        public \DateTimeInterface $birthday,
    ) {
        parent::__construct($name, $lastName);
    }
}
