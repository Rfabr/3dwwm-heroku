<?php

namespace App\Form\Frontend;

use App\DTO\CreditCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du titulaire de la carte',
                    'class' => 'input-card-name'
                ]
            ])
            ->add('number', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '1234 5678 9012 3456',
                    'pattern' => '[0-9]{16}',
                    'class' => 'input-card-number'
                ]
            ])
            ->add('expiryMonth', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'MM',
                    'pattern' => '[0-9]{2}',
                    'class' => 'input-card-expiry'
                ]
            ])
            ->add('expiryYear', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'AA',
                    'pattern' => '[0-9]{2}',
                        'class' => 'input-card-expiry'
                ]
            ])
            ->add('cryptogram', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'CVV',
                    'pattern' => '[0-9]{3}',
                    'class' => 'input-card-cryptogram'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
                'attr' => [
                    'class' => 'button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class
        ]);
    }
}
