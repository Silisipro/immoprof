<?php


namespace App\Form;
use App\Entity\Standing;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CfLogeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenom', TextType::class, [
                'label' => 'Nom et prénoms (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Adresse du logement",
                    'class' => 'form-control',
                ]
            ])
            ->add('typeLogement', TextType::class, [
                'label' => 'Type de logement (*)',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Ex: Villa",
                    'class' => 'form-control',
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
            ->add('detail', TextareaType::class, [
                'label' => 'Plus de détails',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Caractéristiques du logement',
                    'rows' => '3',
                    'style' => 'width: 100%;',
                ],
                'label_attr' => [
                    'style' => 'display: block;',
                ],
            ])
            ->add('files', FileType::class, [
                'label' => "Sélectionner vos images",
                'mapped' => false,
                'required' => false,
                'multiple' => true,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '5120k', // 5 Mo
//                        'mimeTypes' => [
//                            'image/gif',
//                            'image/jpeg',
//                            'image/png',
//                            'image/svg+xml',
//                        ],
//                        'mimeTypesMessage' => "Veuillez choisir des images dont chacune d'elle a une taille maximale de 5 Mo",
//                    ])
//                ],
                'attr' => [
                    'class' => "form-control",
                    'label' => "Déposez vos fichiers ici",
                    'help' => "Ou cliquez pour les téléverser",
                    'is' => "drop-files",
                ],
            ])
            ->add('dateHeureRdv', DateType::class, [
                'label' => 'Programmer un rendez-vous',
                'mapped' => false,
                'required' => false,
                'label_attr' => [
                    'style' => 'display: block;',
                ],
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'flatpicker',
                ],
                'format' => 'yyyy-MM-dd HH:mm',
            ])
            ->add('typeRdv', ChoiceType::class, [
                'choices' => [
                    'Téléphonique' => 'Téléphonique',
                    'Visioconférence' => 'Visioconférence',
                    'Physique' => 'Physique',
                ],
                'label' => 'Type de rendez-vous souhaité',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'label_attr' => [
                    'style' => 'display: block;',
                ],
                'placeholder' => false,
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