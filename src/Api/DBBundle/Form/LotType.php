<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class LotType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lot = $builder->getData();
        $quantityOptions = array(
            'label'    => 'Qté dispo',
            'mapped'   => false,
            'required' => true
        );
        $imageOptions = array(
            'data_class' => null
        );
        //edit view
        if($lot->getId()){
            $quantityOptions['required'] = false;
            $quantityOptions['disabled'] = true;
            $quantityOptions['data'] = $lot->getQuantity();
            $builder->add('newQuantity',TextType::class,array(
                'label'    => false,
                'mapped'   => false,
                'required' => false
            ));
        } else {
            $quantityOptions['constraints'] = new NotBlank();
            $imageOptions['constraints'] = new NotBlank();
        }
        $builder
            ->add('nomLot')
            ->add('nbPointNecessaire')
            ->add('description')
            ->add('quantity',TextType::class,$quantityOptions)
            ->add('nomLong',null,array(
                'required'    => true,
                'constraints' => new NotBlank()
            ))
            ->add('cheminImage', FileType::class, $imageOptions)
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
