<?php

namespace Api\DBBundle\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

class MvtCreditValidator extends ConstraintValidator
{
   /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityManager
     */
    protected $em;
    
    public function __construct(RequestStack $requestStack, EntityManager $em){
        $this->requestStack = $requestStack;
        $this->em = $em;
    }
    
    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();
        //user id
        $userId = $request->get('id_utilisateur');
        $user = $this->em->getRepository('ApiDBBundle:Utilisateur')->find($userId);
        if($user){
            $balance = $user->getLastBalance();
            $out = $value->getSortieCredit();
            if(!empty($out) && ($out > $balance)){
                $this->context->buildViolation('La sortie de crédit ne devrait pas dépasser le solde du crédit')
                        ->atPath('sortieCredit')
                        ->addViolation();
            }
        }
    }
}
