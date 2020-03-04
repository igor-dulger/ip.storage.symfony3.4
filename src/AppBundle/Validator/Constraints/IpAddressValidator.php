<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IpAddressValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IpAddress) {
            throw new UnexpectedTypeException($constraint, IpAddress::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // if (!is_string($value)) {
        //     throw new UnexpectedValueException($value, 'string');
        // }

        $ip_regexp = "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";

        if (!preg_match($ip_regexp, $value)) {
            $this->context->buildViolation($constraint->message)
                // ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}