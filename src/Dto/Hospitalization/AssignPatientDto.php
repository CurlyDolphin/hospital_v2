<?php

namespace App\Dto\Hospitalization;
use Symfony\Component\Validator\Constraints as Assert;

class AssignPatientDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $patientId,

        #[Assert\NotBlank]
        public int $wardId,
    )
    {
    }
}