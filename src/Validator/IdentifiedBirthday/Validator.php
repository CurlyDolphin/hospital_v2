<?php

namespace App\Validator\IdentifiedBirthday;

use App\Dto\Patient\CreatePatientDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Validator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IdentifiedBirthday) {
            throw new \InvalidArgumentException('Неверный тип ограничения');
        }

        if ($value instanceof CreatePatientDto && !$value->isIdentified && null !== $value->birthday) {
            $this->context->buildViolation($constraint->message)
                ->atPath('birthday')
                ->addViolation();
        }
    }
}
