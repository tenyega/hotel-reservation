<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchType extends AbstractType
{
    protected $formfactory;

    public function __construct(FormFactoryInterface $formfactory)
    {
        $this->formfactory = $formfactory;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'placeholder' => "Date d'arrivée"

            ])
            ->add("departureDate", DateType::class, [
                'widget' => 'single_text',
                'label' => false
            ])
            ->add("rooms", ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8'
                ],
                'placeholder' => "Nombre des chambres"
            ])
            ->add("adults", ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10'
                ],
                'placeholder' => "Nombre des Adult"
            ])
            ->add("enfants", ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ],
                'placeholder' => "Nombre des Enfants"
            ])
            // ->add("tags", CollectionType::class, [

            //     'entry_type' => ChoiceType::class,
            //     'entry_options' => [
            //         [
            //             'label' => false,
            //             'choices' => [
            //                 '1' => '1',
            //                 '2' => '2',
            //                 '3' => '3',
            //                 '4' => '4',
            //                 '5' => '5',
            //                 '6' => '6',
            //                 '7' => '7',
            //                 '8' => '8'
            //             ],
            //             'placeholder' => "Nombre des chambres"
            //         ],
            //         [
            //             'label' => false,
            //             'choices' => [
            //                 '1' => '1',
            //                 '2' => '2',
            //                 '3' => '3',
            //                 '4' => '4',
            //                 '5' => '5',
            //                 '6' => '6',
            //                 '7' => '7',
            //                 '8' => '8'
            //             ],
            //             'placeholder' => "Nombre des adults"
            //         ],
            //         [
            //             'label' => false,
            //             'choices' => [
            //                 '1' => '1',
            //                 '2' => '2',
            //                 '3' => '3',
            //                 '4' => '4',
            //                 '5' => '5',
            //                 '6' => '6',
            //                 '7' => '7',
            //                 '8' => '8'
            //             ],
            //             'placeholder' => "Nombre des enfants"
            //         ]
            //     ]
            // ])

            ->add("codePromo", TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Code Promotionnal"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getFormView()
    {
        $builder = $this->formfactory->createBuilder(ReservationType::class);
        $form = $builder->getForm();
        return $form->createView();
    }
}
