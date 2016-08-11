<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailSite')
            ->add('nomExpediteur')
            ->add('seuriteSMTP', ChoiceType::class, array(
                'choices' => array(
                    'TLS' => 'TLS',
                    'SSL' => 'SSL'
                )
            ))
            ->add('portSMTP')
            ->add('userSMTP')
            ->add('passwordSMTP')
            ->add('serveurSMTP')
            ->add('adresse_societe')
            ->add('template_inscription')
            ->add('subject_inscription')
            ->add('template_mdp_oublie')
            ->add('subject_mdp_oublie')    
            
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\ParameterMail'
        ));
    }
}
