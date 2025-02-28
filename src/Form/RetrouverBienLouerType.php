<?php

namespace App\Form;
use App\Entity\TypeBien;
use App\Entity\Standing;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetrouverBienLouerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'Lieu',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse du logement',
                    'class' => 'form-control',
                ]
            ])
            ->add('typeBien', EntityType::class, [
                'class' => TypeBien::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere("t.categorie = 'a_louer'")
                        ->orderBy('t.type', 'ASC');
                },
                'choice_label' => 'type',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de bien (*)',
                'required' => true,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control select2',
                ]
            ])
            ->add('standing', EntityType::class, [
                'class' => Standing::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Standing',
                'required' => false,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('price', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Budget maximal',
                    'class'=>'form-control mb-4',
                ],
                'constraints'=>[ 
                    new Assert\Positive(),
                    new Assert\LessThan(10000001)
                     ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}