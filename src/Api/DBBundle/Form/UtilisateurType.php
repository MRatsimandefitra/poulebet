<?php

namespace Api\DBBundle\Form;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
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
            //->add('password', PasswordType::class, array('required'=>false))
            //->add('salt')
            ->add('isEnable')
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, array(
                "choices"=>array(
                    "Masculin"=>"0",
                    "Féminin"=>"1"
                )
            ))
            ->add('dateNaissance', DateType::class, array())
            /* ->add('createdAt', DateTimeType::class, array())
             ->add('updatedAt', DateTimeType::class, array())*/
            //->add('userToken')
            //->add('cheminPhoto')
             ->add('achatProno')
            /*->add('dateProno', 'datetime')
            ->add('validiteProno', 'datetime')*/
            ->add('adresse1')
            ->add('adresse2')
            ->add('adresse3')
            ->add('pays')
            ->add('telephone')
            ->add('fax')
            ->add('ville');
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
