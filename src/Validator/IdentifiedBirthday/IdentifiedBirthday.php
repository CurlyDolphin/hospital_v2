<?php

namespace App\Validator\IdentifiedBirthday;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IdentifiedBirthday extends Constraint
{
    public string $message = 'Birthday can be set only for identified users.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return Validator::class;
    }
}
