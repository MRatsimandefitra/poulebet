<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Api\DBBundle\Form\Validator\Constraints\MvtCreditConstraint;

class MvtCreditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entreeCredit')
            ->add('sortieCredit')
            ->add('typeCredit', TextType::class,array(
                'label' => 'Type de credit',
                'required' => true
            ))
            ->add('soldeCredit', TextType::class,array(
                'mapped'   => false,
                'required' => false,
                'disabled' => true
            ))
  
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\MvtCredit',
            'constraints' => new MvtCreditConstraint()
        ));
    }
}
