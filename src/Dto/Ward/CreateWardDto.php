<?php

namespace App\Dto\Ward;

use Symfony\Component\Validator\Constraints as Assert;

class CreateWardDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Range(min: 1, max: 999)]
        public int $wardNumber,

        #[Assert\NotBlank]
        public string $description,
    )
    {

    }
}