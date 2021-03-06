<?php

namespace Api\DBBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DroitAdminRoleType extends AbstractType
{
    const FORM_ADMIN = 'Api\DBBundle\Form\AdminType';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lecture')
            ->add('modification')
            ->add('suppression')
            ->add('ajout')
            ->add('droit', EntityType::class, array(
                'class' => 'ApiDBBundle:Droit',
                'choice_label' => 'fonctionnalite'

            ))/*->add('admin', self::FORM_ADMIN);*/
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\DroitAdmin'
        ));
    }
}
