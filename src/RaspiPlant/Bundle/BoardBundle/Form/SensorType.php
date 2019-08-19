<?php

namespace RaspiPlant\Bundle\BoardBundle\Form;

use RaspiPlant\Bundle\BoardBundle\Entity\Device;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SensorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, array('label' => 'Name'))
            ->add('class', Type\TextType::class, array('label' => 'Class'))
            ->add('pin', Type\IntegerType::class, array('label' => 'Pin', 'required' => false))
            ->add('active', Type\CheckboxType::class, array('label' => 'isActive', 'required' => false))
            ->add('device', EntityType::class, array('label' => 'Device', 'class' => Device::class))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RaspiPlant\Bundle\BoardBundle\Entity\Sensor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fullvibes_bundle_boardbundle_sensor';
    }


}
