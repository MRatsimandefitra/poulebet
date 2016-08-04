<?php

namespace Api\DBBundle\Form;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailBoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //    ->add('username', TextType::class, array())
            ->add('email', EntityType::class, array(
                'class' => 'ApiDBBundle:Utilisateur',
                'choice_label' => 'email'
            ))
            ->add('password', PasswordType::class, array())
            //->add('salt')
            //->add('roles')
            //->add('isEnable')
        /*    ->add('nom')
            ->add('prenom')
            ->add('sexe')
            ->add('dateNaissance', DateType::class, array())
            ->add('dateCreation', DateType::class, array())*/
            /* ->add('createdAt', DateTimeType::class, array())
             ->add('updatedAt', DateTimeType::class, array())*/
            //->add('userToken')
            //->add('cheminPhoto')
            /* ->add('achatProno')
             ->add('dateProno', 'datetime')
             ->add('validiteProno', 'datetime')*/

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Utilisateur'
        ));
    }
}
