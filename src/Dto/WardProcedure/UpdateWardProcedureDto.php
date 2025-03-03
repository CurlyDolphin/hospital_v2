<?php

namespace App\Dto\WardProcedure;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateWardProcedureDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Procedure list must not be empty')]
        #[Assert\All([
            new Assert\Collection([
                'fields' => [
                    'procedure_id' => [
                        new Assert\NotBlank(message: 'Procedure ID is required'),
                        new Assert\Type(type: 'integer', message: 'Procedure ID must be a number'),
                    ],
                    'sequence' => [
                        new Assert\NotBlank(message: 'Procedure sequence is required'),
                        new Assert\Type(type: 'integer', message: 'Procedure sequence must be a number'),
                        new Assert\GreaterThanOrEqual(value: 1, message: 'Procedure sequence must be greater than or equal to 1'),
                    ],
                ],
                'allowExtraFields' => false,
            ]),
        ])]
        public array $procedures = [],
    ) {
    }
}
