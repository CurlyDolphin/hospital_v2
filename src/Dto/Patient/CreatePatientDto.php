<?php

namespace App\Dto\Patient;

use App\Validator\IdentifiedBirthday;
use Symfony\Component\Validator\Constraints as Assert;


#[IdentifiedBirthday]
class CreatePatientDto
{
    public function __construct(
        #[Assert\Length(
            min: 1,
            max: 80,
            minMessage: "Имя должно иметь минимум 1 символ",
            maxMessage: "Имя не должно быть длинее 80 символов."
        )]
        public string $name,

        #[Assert\Length(
            min: 1,
            max: 80,
            minMessage: "Фамилия должна иметь минимум 1 символ",
            maxMessage: "Фамилия не должна быть длинее 80 символов."
        )]
        public string $lastName,

        #[Assert\Choice(['male', 'female', 'other'], message: "Пол должен быть задан")]
        public string $gender,

        #[Assert\NotNull]
        public bool $isIdentified = true,

        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: "Дата рождения не может быть позже текущей даты"
        )]
        public ?\DateTimeInterface $birthday = null,

        #[Assert\Type(type: "integer")]
        public ?int $cardNumber = null
    )
    {

    }
}
