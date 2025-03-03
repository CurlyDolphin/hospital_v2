<?php

namespace App\Dto\Procedure;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProcedureDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string', message: 'Name must be a string"')]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Type('string', message: 'Description must be a string')]
        public string $description,
    ) {
    }
}
