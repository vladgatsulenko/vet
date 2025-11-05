<?php

namespace App\Form;

use App\Entity\AnimalSpecies;
use App\Entity\PharmacologicalGroup;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('descriptionShort')
            ->add('descriptionMedium')
            ->add('descriptionFull')
            ->add('ingredients')
            ->add('pharmacologicalProperties')
            ->add('indicationsForUse')
            ->add('dosageAndAdministration')
            ->add('restrictions')
            ->add('pharmacologicalGroup', EntityType::class, [
                'class' => PharmacologicalGroup::class,
                'choice_label' => 'name',
            ])
            ->add('animalSpecies', EntityType::class, [
                'class' => AnimalSpecies::class,
                'choice_label' => 'name',
                'required' => false,
            ])
        ;

        $builder->setEmptyData(function (FormInterface $form) {
            return new Product(
                $form->get('name')->getData(),
                $form->get('pharmacologicalGroup')->getData(),
                $form->get('animalSpecies')->getData()
            );
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
