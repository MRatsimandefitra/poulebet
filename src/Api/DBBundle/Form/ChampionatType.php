<?php

namespace Api\DBBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('isEnable', CheckboxType::class, array(
                'required' => false,
                'data' => true
            ))
            ->add('nomChampionat', TextType::class)
            ->add('fullNameChampionat', TextType::class)
            ->add('pays', TextType::class, array('required' => false))

            ->add('rang', ChoiceType::class, array(
                'choices' => $this->dataNb,
                'data' => max($this->dataNb)
            ))
            //->add('season', TextType::class, array())

        ;
        if($options['edit'] = true){
            $builder
                ->add('isEnable', CheckboxType::class, array(
                    'required' => false,
                    'data' => true
                ))
                ->add('nomChampionat', TextType::class)
                ->add('fullNameChampionat', TextType::class)
                ->add('pays', TextType::class, array('required' => false))
                ->add('rang', ChoiceType::class, array(
                    'choices' => $this->dataNb,
                ))

            ;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Api\DBBundle\Entity\Championat',
            'nbChampionat' => null,
            'edit' => null
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
