<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class ChampionatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomChampionat', TextType::class)
            ->add('fullNameChampionat', TextType::class)
            ->add('typeChampionat', ChoiceType::class, array(
                'choices' => array(
                    'International' => 'International',
                    'National' => 'National',
                    'Regional' => 'Regional'
                ),
                'placeholder' => 'Choisir le type championat',
                'empty_data'  => null

            ))
            ->add('dateDebutChampionat', DateType::class, array(
                    /*'data' => \Daye::class'2016-01-01'*/
                   /* 'widget' => 'choice',
                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'datepic'],*/
                /*'format' => 'yyyy-MM-dd'*/
            ))
            ->add('dateFinaleChampionat', DateType::class, array(
                    /*'widget' => 'single_text',
                    // do not render as type="date", to avoid HTML5 date pickers
                    'html5' => false,
                    // add a class that can be selected in JavaScript
                    'attr' => ['class' => 'datepic'],*/
                    /*'format' => 'yyyy-MM-dd'*/
            ))
           /* ->add('teamsPays', EntityType::class, array(
                'class' => 'ApiDBBundle:TeamsPays',
                'choice_label' => 'fullName',
                'multiple' => true
            ))*/
            ->add('pays', CountryType::class, array(
               'placeholder' => 'Choisir un pays',
               'empty_data' => null,
               'required' => false
           ))

            ->add('season', TextType::class, array())

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Championat'
        ));
    }
}
