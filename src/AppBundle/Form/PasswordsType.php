<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'user.password',
                        'attr' => ['class' => 'form-control']
                    ],
                    'second_options' => [
                        'label' => 'user.password_repeat',
                        'attr' => ['class' => 'form-control']
                    ],
                ]
            )
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => ['class' => 'btn btn-success', 'button_icon' => 'save'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
