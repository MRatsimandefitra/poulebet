<?php

namespace Api\DBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          //  ->add('id')
            ->add('dateMatch', DateTimeType::class, array(
              'data_class' => null
          ))
            ->add('equipeDomicile')
            ->add('cheminLogoDomicile', FileType::class, array(
                'data_class' => null
            ))
            ->add('score')
            ->add('equipeVisiteur')
            ->add('cheminLogoVisiteur', FileType::class, array(
                'data_class' => null
            ))
            ->add('cot1Pronostic')
            ->add('coteNPronistic')
            ->add('cote2Pronostic')
         //   ->add('statusMatch')
        //    ->add('masterProno')
            ->add('masterProno1')
            ->add('masterPronoN')
            ->add('masterProno2')
            ->add('resultatDomicile')
            ->add('resultatVisiteur')
            ->add('tempsEcoules')
         //   ->add('vote1Concours')
        //    ->add('coteNConcours')
        //    ->add('cote2Concours')
        //    ->add('championat')
       //     ->add('concours')
        //    ->add('lotoFoot7')
        //    ->add('lotoFoot15')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Matchs'
        ));
    }
}
