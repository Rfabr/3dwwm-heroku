<?php

namespace App\Form\Frontend;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ModifyPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('current_password', PasswordType::class, [
                'label' => 'MOT DE PASSE ACTUEL',
                'mapped' => false,
                'attr' => [
                    'class' => 'input-password'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => false,
                                    'attr' => [
                                        'placeholder' => '6 caractères minimum',
                                        'class' => 'input-password'
                                    ],
                                    'constraints' => new Length(['min' => 6,
                                                                 'minMessage' => ': votre nouveau mot de passe doit contenir au minimum 6 caractères.',
                                                                 'max' =>255
                                    ])
                ],
                'second_options' => ['label' => 'CONFIRMEZ MOT DE PASSE',
                                     'attr' => [
                                         'placeholder' => 'Répétez le mot de passe',
                                         'class' => 'input-password'
                                     ]
                ],
                'invalid_message' => ': mots de passe différents.'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mot de passe',
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
