<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class PhoneNumber extends Constraint
{
    public string $message = 'Некорректный номер телефона: "{{ value }}"';

    public function validatedBy(): string
    {
        return PhoneNumberValidator::class;
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
