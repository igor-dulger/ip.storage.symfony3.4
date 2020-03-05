<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class IpAddress extends Constraint
{
    public $message = 'Invalid ip address.';
}