<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'50'
                      ],
                'label'=> 'Nom/Prenom',
                'label_attr'=>[
                        'class' =>'form-label'
                            ],
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>60]),
                    new Assert\NotBlank()
                    ]  
                ])

            ->add('telephone', TelType::class, [
                'attr'=> [
                    'class'=>'form-control'
                     ],
               'label'=> 'Numéro de téléphone',
               'label_attr'=>[
                       'class' =>'form-label mt-4'
                           ],
               'constraints'=> [
                   new Assert\NotNull(),
               ]
            ])
            ->add('lieu', TextType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'50'
                      ],
                'required'=> false,
                'label'=> 'Lieu de residence ',
                'label_attr'=>[
                    'class' =>'form-label mt-4'
                        ],
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>60]),
                    ]  
                ])

            ->add('email', EmailType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'180'
                      ],
                'label'=> 'Adresse Email',
                'label_attr'=>[
                        'class' =>'form-label mt-4'
                            ],
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>180]),
                    new Assert\NotBlank(),
                    new Assert\Email()
                    ]  
                ])
            ->add('plainPassword', RepeatedType::class,[
                 'type'=> PasswordType::class,
                 'first_options'=> [ 
                     'attr'=> [
                        'class'=>'form-control'
                     ],
                     'label'=> 'Mot de passe',
                     'label_attr'=>[
                        'class' =>'form-label mt-4'
                    ]
                ],
                  'second_options'=> [ 
                    'attr'=> [
                        'class'=>'form-control'
                    ],
                    'label'=> 'Confirmation du mot de passe',
                     'label_attr'=>[
                        'class' =>'form-label mt-4'
                    ]
                ],

                'invalid_message'=> 'Les mots de passe ne correspondent pas'        
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
