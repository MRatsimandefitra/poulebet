<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array())
            ->add('email', EmailType::class, array())
            ->add('password', PasswordType::class, array())
            //->add('roles')
            //->add('salt')
            //->add('isEnable')
            ->add('nom', TextType::class, array())
            ->add('prenom', TextType::class, array())
            //->add('createdAt', DateTimeType::class)
            //->add('updatedAt', 'datetime')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Admin'
        ));
    }
}
