<?php

namespace App\Form;
use App\Entity\TypeBien;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetrouverBienVendreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu', TextType::class, [
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
                        ->andWhere("t.categorie = 'a_vendre'")
                        ->orderBy('t.type', 'ASC');
                },
                'choice_label' => 'type',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de bien (*)',
                'required' => true,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
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