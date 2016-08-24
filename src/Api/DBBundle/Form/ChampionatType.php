<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'int' => 'international',
                    'nat' => 'national',
                    'reg' => 'regional'
                )
            ))
            ->add('dateDebutChampionat', DateType::class)
            ->add('dateFinaleChampionat', DateType::class)
            ->add('teamsPays', EntityType::class, array(
                'class' => 'ApiDBBundle:TeamsPays',
                'choice_label' => 'fullName',
                'multiple' => true
            ))
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
