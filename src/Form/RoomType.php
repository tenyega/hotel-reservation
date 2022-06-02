<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                'label' => 'Chambre Numero',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Le numero de la chambre ne peut pas etre vide"])
                ],
            ])
            ->add('Floor', NumberType::class, [
                'label' => 'Chambre Etage',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Le etage de la chambre ne peut pas etre vide"])
                ],
            ])
            ->add('MaxCapacity', NumberType::class, [
                'label' => 'Chambre Capacité',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "La capacité peut etre minimum 1"])
                ],
            ])
            ->add('Type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    "Climatisation" => '1',
                    "Sans Climatisation" => '2'
                ],
                'placeholder' => "Type de la chambre",
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser le Type"])
                ]
            ])
            ->add('IsSmoking', ChoiceType::class, [
                'label' => "Fumeuse ?",
                'choices' => [
                    "Oui" => '1',
                    "Non" => '2'
                ],
                'placeholder' => "Chambre Fumeuse",
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser si la chambre est fumeuse"])
                ]
            ])
            ->add('FacilityPossible', CheckboxType::class, [
                'label'    => 'Services?',

                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez selectionnez au moins un service"])
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de Produit',
                'attr' => ['placeholder' => 'Tapez le prix de produit'],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez indiquer le prix per nuit"])
                ]
            ])
            ->add('bedding', ChoiceType::class, [
                'label' => "Type de Lit",
                'choices' => [
                    "1 X Lit Simple" => '1',
                    "1 X Lit Double" => '2'
                ],
                'placeholder' => "Type de Lit",
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser si le lit"])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de la Chambre ",
                'attr' => [
                    'placeholder' => "Description"
                ],
                'required' => false
            ])
            ->add('mainPicture')
            ->add('otherPicture')
            ->add('anotherpicture');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
