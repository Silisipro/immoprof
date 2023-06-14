<?php

namespace App\Form;

use App\Entity\TypeBien;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [ 
                'attr'=> [
                    'class' =>'form-control',
                     'minlength' =>'2',
                     'maxlength' =>'255'
                      ],
                'label'=> 'Type de bien',
                'constraints'=> [
                    new Assert\Length(['min'=>2, 'max'=>255]),
                    new Assert\NotBlank()
                    ]
        
                ])
                ->add('chois', ChoiceType::class, [
                    'choices' => [
                        'A louer' => 'a_louer',
                        'A vendre' => 'a_vendre',
                    ],
                    'label' => 'Choisir le type du bien',
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false,
                    'label_attr' => [
                    ],
                    'placeholder' => false,
                ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeBien::class,
        ]);
    }
}
