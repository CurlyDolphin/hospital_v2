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

        #[Assert\Choice(['male', 'female', 'other'], message: 'Birthday must be provided')]
        public string $gender,

        #[Assert\NotNull]
        public bool $isIdentified = true,

        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: 'Birthday cannot be later than today'
        )]
        public ?\DateTimeInterface $birthday = null,

        #[Assert\Type(type: 'integer')]
        public ?int $cardNumber = null,
    ) {
        parent::__construct($name, $lastName);
    }
}
