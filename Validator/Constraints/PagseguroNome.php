<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PagseguroNome extends Constraint
{
    public $message = 'Nome inválido: "%string%". Por favor, informe seu nome e sobrenome no campo.';
}
