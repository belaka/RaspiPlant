<?php

namespace FullVibes\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;


/**
 * Description of AtomizerFormType
 *
 * @author belaka
 */
class AtomizerFormType extends AbstractType  {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state', Type\ChoiceType::class, array(
                'choices' => array(
                    'ON' => 1, 
                    'OFF' => 0
                ),
                'expanded'  => true,
                'multiple'  => false,
                'data' => 1
            ))
            ->add('save', Type\SubmitType::class)
        ;
    }
}
