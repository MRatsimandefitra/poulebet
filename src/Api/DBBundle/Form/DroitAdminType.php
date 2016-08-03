<?php

namespace Api\DBBundle\Form;

use Nelmio\ApiDocBundle\Tests\Fixtures\Form\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DroitAdminType extends AbstractType
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
            ->add('droit', EntityType::class, array(/*'class' => 'ApiDBBundle:Droit',*/

            ))
            ->add('admin', self::FORM_ADMIN);
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
