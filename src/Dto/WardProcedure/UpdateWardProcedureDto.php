<?php

namespace App\Dto\WardProcedure;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateWardProcedureDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Список процедур не должен быть пустым')]
        #[Assert\All([
            new Assert\Collection([
                'fields' => [
                    'procedure_id' => [
                        new Assert\NotBlank(message: 'Идентификатор процедуры обязателен'),
                        new Assert\Type(type: 'integer', message: 'Идентификатор процедуры должен быть числом'),
                    ],
                    'sequence' => [
                        new Assert\NotBlank(message: 'Порядок процедуры обязателен'),
                        new Assert\Type(type: 'integer', message: 'Порядок процедуры должен быть числом'),
                        new Assert\GreaterThanOrEqual( value: 1, message: 'Порядок процедуры должен быть больше или равен 1'),
                    ],
                ],
                'allowExtraFields' => false,
            ])
        ])]
        public array $procedures = [],
    )
    {
    }
}
