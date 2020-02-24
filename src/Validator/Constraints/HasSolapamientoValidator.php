<?php
// src/Validator/Constraints/HasSolapaminetoValidator.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class HasSolapamientoValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        
        if (!$constraint instanceof HasSolapamiento) {
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        //#if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
        //#    throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        //#}

        //#if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
        if ( true )  {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
