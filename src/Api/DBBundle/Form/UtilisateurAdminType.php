<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurAdminType extends AbstractType
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
            ->add('password', PasswordType::class, array('required' => false))
            //->add('salt')
            //->add('roles')
            ->add('isEnable', CheckboxType::class, array())
            ->add('nom', TextType::class, array())
            ->add('prenom',TextType::class, array() )
            ->add('sexe', ChoiceType::class, array(
                'choices' => array(
                    'Femme' => 0,
                    'Homme' => 1
                )
            ))
            ->add('dateNaissance', DateType::class, array())
          //  ->add('dateCreation', DateType::class, array())
            /*->add('createdAt', 'datetime')
            ->add('updatedAt', 'datetime')
            ->add('userToken')
            */
                // To do:
            //->add('cheminPhoto', FileType::class, array())
            ->add('achatProno', CheckboxType::class, array('required' => false))
            ->add('adresse1', TextType::class, array('required' =>false))
            ->add('adresse2', TextType::class, array('required' =>false))
            ->add('adresse3', TextType::class, array('required' =>false))
            ->add('pays', CountryType::class, array('required' =>false))
            ->add('telephone', NumberType::class, array('required' =>false))
            ->add('fax', NumberType::class, array('required' =>false))
            ->add('ville', EntityType::class, array(
                'class' => 'ApiDBBundle:Ville',
                'choice_label' => 'nomVille'
            ))
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
