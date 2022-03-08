<?php

namespace App\Form\Frontend;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Ex : Maison, Travail...",
                    'class' => 'input-address'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Prénom",
                    'class' => 'input-address-width'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Nom de famille",
                    'class' => 'input-address-width'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Adresse",
                    'class' => 'input-address'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Code postal",
                    'class' => 'input-address-width'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Ville",
                    'class' => 'input-address-width'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'preferred_choices' => ['FR'],
                'attr' => [
                    'class' => 'input-address'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Numéro de téléphone",
                    'class' => 'input-address'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
