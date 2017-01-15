<?php
namespace Cangulo\UtilBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cpf extends Constraint
{
    public $message = 'CPF inválido: "%string%".';
}
