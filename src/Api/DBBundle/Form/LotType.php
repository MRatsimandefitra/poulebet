<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LotType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomLot')
            ->add('nbPointNecessaire')
            ->add('description')
            ->add('cheminImage', FileType::class, array(
                'data_class' => null
            ))
            ->add('lotCategory', EntityType::class, array(
                'class' => 'ApiDBBundle:LotCategory',
                'choice_label' => 'category',
                'empty_data' => ''
            ))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Lot'
        ));
    }
}
