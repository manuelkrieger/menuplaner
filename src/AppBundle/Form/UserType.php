<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [];
        foreach ($options['user_roles'] as $role) {
            $roles[$role->getName()] = $role->getCode();
        }
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'user.username'],
                'label' => 'user.name',
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'user.email'],
                'label' => 'user.email',
            ])
            ->add('userRoles', ChoiceType::class, [
                'label' => 'user.roles',
                'choices' => $roles,
                'multiple' => false,
                'expanded' => true,
                'mapped' => false,
                'required' => true,
                'data' => $options['current_user_role'],
                'choice_translation_domain' => false
            ]);
        if ($options['data']->getId() == null) {
            $builder->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'user.password',
                    'attr' => ['class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'user.password_repeat',
                    'attr' => ['class' => 'form-control']
                ]
            ]);
        }
        $builder->add('save', SubmitType::class, [
            'label' => 'save',
            'attr' => ['class' => 'btn btn-success'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'current_user_role' => null,
            'user_roles' => null,
            'em' => null
        ]);
    }
}
