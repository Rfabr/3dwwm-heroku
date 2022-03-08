<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'PRÉNOM',
            'attr' => [
                'class' => 'input-register'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label' => 'NOM DE FAMILLE',
            'attr' => [
                'class' => 'input-register'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'E-MAIL',
            'attr' => [
                'class' => 'input-register'
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => false,
                                'attr' => [
                                    'placeholder' => '6 caractères minimum',
                                    'class' => 'input-register'
                                ],
                                'constraints' => new Length(['min' => 6,
                                                             'minMessage' => ': votre mot de passe doit contenir au minimum 6 caractères.',
                                                             'max' => 255
                                ])
            ],
            'second_options' => ['label' => 'CONFIRMEZ MOT DE PASSE',
                                 'attr' => [
                                     'placeholder' => 'Répétez le mot de passe',
                                     'class' => 'input-register'
                                 ]
            ],
            'invalid_message' => ': mots de passe différents.',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Inscription',
            'attr' => [
                'class' => 'button'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
