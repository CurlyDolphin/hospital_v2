<?php

namespace App\Dto\Patient;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePatientDto
{
    public function __construct(
        #[Assert\Length(
            min: 1,
            max: 80,
            minMessage: 'Name must have at least 1 character"',
            maxMessage: 'Name must not be longer than 80 characters.'
        )]
        public ?string $name = null,

        #[Assert\Length(
            min: 1,
            max: 80,
            minMessage: 'Last name must have at least 1 character.',
            maxMessage: 'Last name must not be longer than 80 characters.'
        )]
        public ?string $lastName = null,

        #[Assert\Type(type: 'integer')]
        public ?int $wardId = null,
    )
    {
    }
}
