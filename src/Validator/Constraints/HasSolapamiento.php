<?php
// src/Validator/Constraints/HasSolapamiento.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasSolapamiento extends Constraint
{
    public $message = 'El evento que intenta guardar tiene solapamientos';
}
