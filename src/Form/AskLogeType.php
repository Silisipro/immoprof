<?php

namespace App\Form;
use App\Entity\Standing;
use App\Entity\TypeBien;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AskLogeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenom', TextType::class, [
                'label' => 'Nom et prénoms (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Adresse du logement',
                    'class' => 'form-control',
                ]
            ])
            ->add('typeBien', EntityType::class, [
                'class' => TypeBien::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.type', 'ASC');
                },
                'choice_label' => 'type',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de logement (*)',
                'required' => true,
                'mapped' => false,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display: block; width: 100%;',
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
                'mapped' => false,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display: block; width: 100%;',
                ]
            ])
            ->add('loyer', NumberType::class, [
                'label' => 'Loyer mensuel ou Budget (*)',
                'required' => true,
                'mapped' => false,
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ],
            ])
            ->add('detail', TextareaType::class, [
                'label' => 'Plus de détails',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Caractéristiques du logement',
                    'rows' => '4',
                    'style' => 'width: 100%;',
                ],
                'label_attr' => [
                    'style' => 'display: block;',
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