<?php
namespace App\Validator\IdentifiedBirthday;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class IdentifiedBirthday extends Constraint
{
    public string $message = 'Дата рождения может быть задана только для идентифицированных пользователей.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return Validator::class;
    }
}