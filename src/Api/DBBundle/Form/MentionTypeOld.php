<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mentionLegale')
            ->add('cgv')
            ->add('cgps')
            ->add('cgu')
            ->add('appyone_url_login')
            ->add('appyone_username')
            ->add('appyone_password')
            ->add('appyone_nid')
            ->add('appyone_url_details')
            ->add('gcm_url_android')
            ->add('api_key_apione')
            ->add('api_key_goalapi')
            ->add('createdAt', 'datetime')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Mention'
        ));
    }
}
