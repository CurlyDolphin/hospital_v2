<?php

namespace App\Dto\Patient;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\IdentifiedBirthday;

#[IdentifiedBirthday]
class IdentifyPatientDto
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

        #[Assert\NotBlank(message: "Дата рождения должна быть задана")]
        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: "Дата рождения не может быть позже текущей даты"
        )]
        public ?\DateTimeInterface $birthday = null,
    )
    {
    }

}
