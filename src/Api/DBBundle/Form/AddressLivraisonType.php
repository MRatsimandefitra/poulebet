<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressLivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pays',null,array(
                    'required' => true
                ))                
                ->add('nomcomplet',null,array(
                    'required' => true
                ))         
                ->add('voie',null,array(
                    'required' => true
                ))         
                ->add('numero',null,array(
                    'required' => true
                ))         
                ->add('codePostal',null,array(
                    'required' => true
                ))         
                ->add('ville',null,array(
                    'required' => true
                ))         
                ->add('region',null,array(
                    'required' => true
                ))         
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\AddressLivraison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'api_dbbundle_addresslivraison';
    }


}
