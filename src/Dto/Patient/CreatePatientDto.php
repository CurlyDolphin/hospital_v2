<?php

namespace App\Dto\Patient;

use App\Validator\IdentifiedBirthday\IdentifiedBirthday;
use Symfony\Component\Validator\Constraints as Assert;

#[IdentifiedBirthday]
class CreatePatientDto extends BasePatientDto
{
    public function __construct(

        string $name,

        string $lastName,

        #[Assert\Choice(['male', 'female', 'other'], message: 'Пол должен быть задан')]
        public string $gender,

        #[Assert\NotNull]
        public bool $isIdentified = true,

        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: 'Дата рождения не может быть позже текущей даты'
        )]
        public ?\DateTimeInterface $birthday = null,

        #[Assert\Type(type: 'integer')]
        public ?int $cardNumber = null,
    ) {
        parent::__construct($name, $lastName);
    }
}
