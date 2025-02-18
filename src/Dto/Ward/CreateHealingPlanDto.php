<?php

namespace App\Dto\Ward;

use Symfony\Component\Validator\Constraints as Assert;

class CreateHealingPlanDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: "integer")]
        public int $procedureId,

        #[Assert\NotBlank]
        #[Assert\Type(type: "integer")]
        public string $order,
    )
    {
    }
}