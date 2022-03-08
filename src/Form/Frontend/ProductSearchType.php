<?php

namespace App\Form\Frontend;

use App\Entity\Category;
use App\Entity\Designer;
use App\DTO\ProductSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Recherche...',
                    'class' => 'input-product-search'
                ]
            ])
            ->add('designer', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Designer::class,
                'placeholder' => 'Designer',
                'choice_value' => 'name',
                'attr' => [
                    'class' => 'input-product-search-width'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'placeholder' => 'Catégorie',
                'choice_value' => 'name',
                'attr' => [
                    'class' => 'input-product-search-width'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'OK',
                'attr' => [
                    'class' => 'button'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}