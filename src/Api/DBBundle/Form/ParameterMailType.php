<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('seuriteSMTP')
            ->add('portSMTP')
            ->add('userSMTP')
            ->add('passwordSMTP')
            ->add('serveurSMTP')/* ->add('createdAt', 'datetime')
            ->add('updatedAt')*/
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
