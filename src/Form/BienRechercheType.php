<?php

namespace App\Form;

use App\Entity\TypeBien;
use App\Entity\BienRecherche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Budget maximal',
                    'class'=>'form-control',
                ],
                'constraints'=>[ 
                    new Assert\Positive(),
                    new Assert\LessThan(10000001)
                     ]
            ]
            )
            ->add('minSurface', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Surface minimale',
                    'class'=>'form-control'
                ],
                'constraints'=>[ 
                    new Assert\Positive(),
                    new Assert\LessThan(40000)
                     ]
            ])

            ->add('typeBien', EntityType::class, [
               'class' => TypeBien::class,
               'required' =>false,
               'label' => false,
               'choice_label' => 'type',
               'multiple' => false,
               'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BienRecherche::class,
            'method'=>'get',
            'csrf_protrction'=> false
        ]);
    }


    public function getBlockPrefix()
    {
        return ''; 
    }




    
}
