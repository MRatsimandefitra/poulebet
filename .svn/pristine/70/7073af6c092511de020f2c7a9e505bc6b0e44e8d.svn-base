<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('salt')
            ->add('roles')
            ->add('isEnable')
            ->add('nom')
            ->add('prenom')
            ->add('sexe')
            ->add('dateNaissance', 'date')
            ->add('dateCreation', 'date')
            ->add('createdAt', 'datetime')
            ->add('updatedAt', 'datetime')
            ->add('userToken')
            ->add('cheminPhoto')
            ->add('achatProno')
            ->add('dateProno', 'datetime')
            ->add('validiteProno', 'datetime')
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
