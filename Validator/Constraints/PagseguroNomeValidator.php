<?php
namespace AppBundle\Validator\Constraints;

use Respect\Validation\Validator as v;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PagseguroNomeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (1 === count(explode(' ', trim($value)))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}



