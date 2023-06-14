<?php

namespace App\Form;

use App\Entity\Bien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('heat')
            ->add('city')
            ->add('adress')
            ->add('sold')
            ->add('etat')
            ->add('CodeFichier')
            ->add('datepublication')
            ->add('datelocationvente')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deleted')
            ->add('user')
            ->add('typeBien')
            ->add('standing')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
