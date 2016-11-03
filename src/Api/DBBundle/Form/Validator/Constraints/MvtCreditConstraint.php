<?php

namespace Api\DBBundle\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class MvtCreditConstraint extends Constraint
{
    public function validatedBy()
    {
        return 'poulebet.mvt_credit_validator';
    }
}
