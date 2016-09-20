<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ConcoursType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime('now');
        $dateDebutModify = $now->modify('next monday');
        $dateDebut = $dateDebutModify->format('Y-m-d');

        $fin = new \DateTime('now');
        $ddF = $fin->modify('next sunday');

        $dddF = $ddF->modify('next sunday');
        $dateFinale = $dddF->format('Y-m-d');

        $builder
            ->add('numero', TextType::class, array(
                'required' => true,
                'label' => 'Numéro concour'
            ))
            ->add('nomConcours', TextType::class, array(

            ))
            ->add('dateDebut', DateType::class, array(
                'label' => 'Date début',
                'attr' => array(
                    'class' => 'datepic'
                ),
                'widget' => "single_text",
            ))
            ->add('dateFinale', DateType::class, array(
                'label' => 'Date Fin',
                'attr' => array(
                    'class' => 'datepic'
                ),
                'widget' => "single_text",
                
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Concours'
        ));
    }
}
