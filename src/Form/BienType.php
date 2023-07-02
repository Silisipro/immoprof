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


class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'A louer' => 'a_louer',
                    'A vendre' => 'a_vendre',
                ],
                'label' => 'Catégorie (*)',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check'
                ]
            ])

            ->add('sold', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => "Voulez-vous afficher ce bien sur la page d'accueil du site ?",
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-check'
                ]
            ])
            ->add('name',  TextType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'50'
                      ],
                'required' => true,
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
                'required' => false,
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
                'required' =>false,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(50)
                    ] 
            ])

            ->add('paiementLouer', ChoiceType::class, [
                'choices' => [
                    'Journalier' => 'Jour',
                    'Semestiel' => 'Semaine',
                    'Mensuel' => 'Mois',
                    'Annuel' => 'Annuel',
                ],
                'label' => "Fréquence de paiement du loyer",
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('typeBienLouer', EntityType::class, [
                'class' => TypeBien::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.categorie = :categorie')
                        ->setParameter('categorie', 'a_louer')
                        ->addOrderBy('t.type', 'ASC')
                        ;
                },
                'choice_label' => function ($typeBien) {
                    return $typeBien->getType(). ' - A louer';
                },
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type du bien immobilier (*)',
                'required' => true,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,

            ]) 
            ->add('floor', IntegerType::class,[ 
                'attr'=> [
                    'class' =>'form-control',
                     'min' =>'1',
                     'max' =>'10'
                      ],
                'label'=> 'Nombre de niveau (1 pour rez)',
                'required' => false,
                'constraints'=>[ 
                   new Assert\Positive(),
                   new Assert\LessThan(10)
                ]
            ])

            ->add('typeBienVendre', EntityType::class, [
                'class' => TypeBien::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.categorie = :categorie')
                        ->setParameter('categorie', 'a_vendre')
                        ->addOrderBy('t.type', 'ASC')
                        ;
                },
                'choice_label' => function ($typeBien) {
                    return $typeBien->getType(). ' - A vendre';
                },
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type du bien immobilier (*)',
                'required' => false,
                'placeholder' => '-- Sélectionner --',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
            ])
            ->add('heat', ChoiceType::class , [
                'attr'=> [
                    'class' =>'form-control'],
                'choices' => $this->getChoices(),
                'label'=>'Type de compteur',
                'required' => false,
                'placeholder' => '-- Sélectionner --',
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
                    'required' => false,
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
            ->add('files', FileType::class, [
                'label' => "Sélectionner vos images",
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'onchange' => 'previewUpload();',
                    'accept' => 'image/*',
                    'class' => 'form-control',
                ],
  //              'help' => "La taille maximale de chaque image sélectionnée doit être de 5 Mo sinon l'image ne sera pas envoyée sur le serveur.",
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


    public function getBlockPrefix()
    {
        return 'bien';
    }

}

