<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Standing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TypeBien;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\VichimageType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class Bien1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',  TextType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'50'
                      ],
                'label'=> 'Titre',
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>255]),
                    new Assert\NotBlank()
                    ]
        
                ] )
            ->add('price', IntegerType::class, [
                'attr'=>[
                    'class' =>'form-control',
                    'minlength' =>'2',
                    'maxlength' =>'50'
                    ],
                'label'=> 'Prix',
                'required' => true,
                'constraints'=>[ 
                    new Assert\Positive(),
                    new Assert\LessThan(10000001)
                    ]
                
            ])
            ->add('description', TextareaType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'5000'
                      ],
                'label'=> 'Description'
            ])
            ->add('surface',IntegerType::class,[ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'4000'
                      ],
                'label'=> 'Surperficie(en m²)',
                'required' => false,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(4000)
                    ] 
            ])
            ->add('rooms', IntegerType::class,[ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'50'
                      ],
                'label'=> 'Nombre de pièces',
                'required' => true,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(50)
                    ] 
            ])
            ->add('bedrooms', IntegerType::class,[ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'50'
                      ],
                'label'=> 'Nombre de chambres ',
                'required' =>true,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(50)
                    ] 
            ])
            ->add('typeBien', EntityType::class, [
                'class' => TypeBien::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.type', 'ASC');
                },
                'choice_label' => function ($typeBien) {
                    $value = $typeBien->getType().' - '.$typeBien->getCategorie();
                    return $value;
                },
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de logement',
                'required' => true,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('floor', IntegerType::class,[ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'10'
                      ],
                'label'=> 'Nombre de niveau',
                'required' => true,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(10)
                    ] 
            ])
            ->add('heat', ChoiceType::class , [
                'attr'=> [
                    'class' =>'form-control'],
                'choices' => $this->getChoices(),
                'label'=>'Type de chauffage'
            ])
            ->add('city', TextType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'255'
                      ],
                'label'=> 'Ville du bien',
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>255]),
                    new Assert\NotBlank()
                    ]
        
                ])
                ->add('standing', EntityType::class, [
                    'class' => Standing::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Standing',
                    'required' => true,
                    'placeholder' => '-- Sélectionner --',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
            ->add('adress', TextType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'255'
                      ],
                'label'=> 'Adresse du bien',
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>255]),
                    new Assert\NotBlank()
                    ]
        
                ])
            ->add('sold',  CheckboxType::class,  [
                'label'=> 'Mettre en favori ?',
                'required' =>false,
                'constraints'=>[ 
                    new Assert\NotNull(), 
                    ]    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }



    private function getChoices()
    {
        $choices = Bien::HEAT;
        $output = [];
        foreach( $choices as $k =>$v){
            $output[$v]=$k;
        }
        return $output;
    }

}

