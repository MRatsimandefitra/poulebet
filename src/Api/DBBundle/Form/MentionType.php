<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('appyone_url_login', TextType::class)
            ->add('appyone_username', TextType::class)
            ->add('appyone_password', TextType::class, array())
            ->add('appyone_nid', TextType::class, array())
            ->add('appyone_url_details', TextType::class, array())
            ->add('gcm_url_android', TextType::class, array())
            ->add('api_key_apione', TextType::class, array())
            ->add('api_key_goalapi', TextType::class, array())
            ->add('appyone_url_liste_application', TextType::class, array())
           /* ->add('mentionLegale', TextareaType::class, array('attr' => array(
                'class' => 'tinyMCE'
            )))*/
            ->add('cgv', TextareaType::class, array('attr' => array(
                'class' => 'tinyMCE'
            )))
            ->add('cgps', TextareaType::class, array('attr' => array(
                'class' => 'tinyMCE'
            )))
            ->add('cgu', TextareaType::class, array('attr' => array(
                'class' => 'tinyMCE'
            )))
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
