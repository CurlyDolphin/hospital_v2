<?php

namespace App\Dto\Hospitalization;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePatientDto
{
    #[Assert\Length(
        min: 1,
        max: 80,
        minMessage: "Имя должно иметь минимум 1 символ",
        maxMessage: "Имя не должно быть длинее 80 символов."
    )]
    public ?string $name = null;

    #[Assert\Length(
        min: 1,
        max: 80,
        minMessage: "Фамилия должна иметь минимум 1 символ",
        maxMessage: "Фамилия не должна быть длинее 80 символов."
    )]
    public ?string $lastName = null;

    #[Assert\Type(type: "integer")]
    public ?int $wardId = null;
}