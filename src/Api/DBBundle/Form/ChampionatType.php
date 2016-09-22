<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
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
    private $nb;
    private $dataNb;
    private $container;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->getDataNb($options['nbChampionat']);
        $builder
            ->add('nomChampionat', TextType::class)
            ->add('fullNameChampionat', TextType::class)
          /*  ->add('typeChampionat', ChoiceType::class, array(
                'choices' => array(
                    'International' => 'International',
                    'National' => 'National',
                    'Regional' => 'Regional'
                ),
                'placeholder' => 'Choisir le type championat',
                'empty_data'  => null

            ))*/
           /* ->add('dateDebutChampionat', DateType::class, array(
                    'data' => new \DateTime('2016-01-01'),
                    'html5' => false,
                    'widget' => 'single_text',
                    'attr' => ['class' => 'datepic', 'style' => "width:200px"]
                'format' => 'yyyy-MM-dd'
            ))*/
           /* ->add('dateFinaleChampionat', DateType::class, array(
                    'data' => new \DateTime('2016-12-31'),
                    'html5' => false,
                    'attr' => ['class' => 'datepic', 'style' => "width:200px"],
                    'widget' => 'single_text',

            ))*/
           /* ->add('teamsPays', EntityType::class, array(
                'class' => 'ApiDBBundle:TeamsPays',
                'choice_label' => 'fullName',
                'multiple' => true
            ))*/
            ->add('pays', TextType::class, array())
            ->add('rang', ChoiceType::class, array(
                'choices' => $this->dataNb
            ))
            //->add('season', TextType::class, array())

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Championat',
            'nbChampionat' => null
        ));
    }

    /**
     * @return mixed
     */
    public function getDataNb($nbChampionat)
    {
        $this->nb = $nbChampionat;
        for($i = 1; $i <= $this->nb; $i++){
            $result[$i] = $i;
        }
        return $this->dataNb = $result;
    }

    /**
     * @param mixed $dataNb
     */
    public function setDataNb($dataNb)
    {
        $this->dataNb = $dataNb;
    }

    public function getEm(){

    }

}
