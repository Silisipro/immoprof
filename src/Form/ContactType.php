<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'style'=>'background-color: aliceblue',
                     'minlength' =>'2',
                     'maxlength' =>'60'
                      ],
                'label'=> 'Nom/Prenom',
                'label_attr'=>[
                        'class' =>'form-label mt-4'
                            ]
                ])
            ->add('email',  EmailType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'style'=>'background-color: aliceblue',
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
            ->add('objet',  TextType::class,[ 
                'attr'=> [
                     'class'=>'form-control',
                     'style'=>'background-color: aliceblue',
                     'minlength' =>'2',
                     'maxlength' =>'255'
                      ],
                'required'=> true,
                'label'=> 'Objet',
                'label_attr'=>[
                        'class' =>'form-label mt-4'
                            ],
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>255]),
                    ]  
                ])
            ->add('message', TextareaType::class, [ 
                'attr'=> [
                     'class'=>'form-control',
                     'style'=>'background-color: aliceblue'
                      ],
                'label'=> 'Votre message',
                'label_attr'=>[
                    'class' =>'form-label mt-4'
                    ],
                'constraints'=>[ 
                    new Assert\NotBlank()
                    ] 
            ])
            ->add('submit', SubmitType::class, [
                'attr' =>[
                    'class'=>'btn btn-primary mb-5',
                    ],
                'label'=> "Envoyer"
                
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
