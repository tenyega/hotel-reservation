<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('RoomNo', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Chambre Numero',
                    'required' => false
                ],
                'constraints' => [
                    new NotBlank(['message' => "Le numero de la chambre ne peut pas etre vide"])
                ],
            ])
            ->add('Floor', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Chambre Etage',
                    'required' => false
                ],
                'constraints' => [
                    new NotBlank(['message' => "Le etage de la chambre ne peut pas etre vide"])
                ],
            ])
            ->add('MaxCapacity', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Capacité Maximale',
                    'required' => false
                ],

                'constraints' => [
                    new NotBlank(['message' => "La capacité peut etre minimum 1"])
                ],
            ])
            ->add('Type', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser le Type"])
                ],
                'choices' => [
                    "Climatisation" => 'Climatisation',
                    "Sans Climatisation" => 'Sans Climatisation'
                ]

            ])
            ->add('IsSmoking', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "Oui" => '1',
                    "Non" => '0'
                ],
                'placeholder' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser si la chambre est fumeuse"])
                ]
            ])
            ->add('FacilityPossible', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Facilité séparé par ','"
                ],
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Tapez le prix de chambre'],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez indiquer le prix per nuit"])
                ]
            ])
            ->add('bedding', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "1 X Lit Simple" => '1 X Lit Simple',
                    "1 X Lit Double" => '1 X Lit Double'
                ],
                'placeholder' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser si le lit"])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Description"
                ],
                'required' => false
            ])
            ->add('mainPicture', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "URL de la photo"
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez mettre au moins une photo"])
                ]
            ])
            ->add('otherPicture', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "URL de la photo"
                ],
                'required' => false,

            ])
            ->add('anotherPicture', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "URL de la photo"
                ],
                'required' => false,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
