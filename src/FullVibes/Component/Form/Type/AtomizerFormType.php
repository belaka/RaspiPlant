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
            ->add('state', Type\CheckboxType::class)
            ->add('save', Type\SubmitType::class)
        ;
    }
}
