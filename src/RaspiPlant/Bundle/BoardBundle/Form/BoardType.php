<?php

namespace RaspiPlant\Bundle\BoardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, array('label' => 'Name'))
            //->add('slug', Type\TextType::class, array('label' => 'Slug'))
            ->add('active', Type\CheckboxType::class, array('label' => 'isActive', 'required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RaspiPlant\Bundle\BoardBundle\Entity\Board'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fullvibes_bundle_boardbundle_board';
    }


}
