<?php

namespace AppBundle\Form;

use AppBundle\Entity\Food;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FoodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', EntityType::class, [
                'label' => 'food.group',
                'attr' => ['class' => 'select2'],
                'class' => 'AppBundle\Entity\Foodgroup',
                'choices' => $options['foodgroups'],
                'choice_label' => 'name'
            ])
            ->add('name', null, [
                'label' => 'food.name',
                'required' => true
            ])
            ->add('unity', EntityType::class, [
                'label' => 'food.unity',
                'class' => 'AppBundle\Entity\Unity',
                'choices' => $options['unities'],
                'choice_label' => 'label'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
            'foodgroups' => null,
            'unities' => null
        ]);
    }
}
