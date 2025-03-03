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

        #[Assert\NotBlank(message: 'The date of birth must be given')]
        #[Assert\Type("\DateTimeInterface")]
        #[Assert\LessThanOrEqual(
            value: new \DateTimeImmutable('today'),
            message: 'Birthday cannot be later than today'
        )]
        public \DateTimeInterface $birthday,
    ) {
        parent::__construct($name, $lastName);
    }
}
