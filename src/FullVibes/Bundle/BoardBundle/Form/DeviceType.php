<?php

namespace FullVibes\Bundle\BoardBundle\Form;

use FullVibes\Bundle\BoardBundle\Entity\Board;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, array('label' => 'Name'))
            //->add('slug', Type\TextType::class, array('label' => 'Slug'))
            ->add('class', Type\TextType::class, array('label' => 'Class'))
            ->add('address', Type\TextType::class, array('label' => 'Address'))
            ->add('active', Type\CheckboxType::class, array('label' => 'isActive', 'required' => false))
            ->add('board', EntityType::class, array('label' => 'Board', 'class' => Board::class))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FullVibes\Bundle\BoardBundle\Entity\Device'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fullvibes_bundle_boardbundle_device';
    }


}
