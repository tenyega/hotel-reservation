<?php

namespace App\Form;

use DateTime;
use App\Entity\Reservation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

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
            ->add('CheckInDate', DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'placeholder' => "Date d'arrivée",
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Date d'arrivée ne peut pas etre vide"]),
                    new GreaterThanOrEqual([
                        'value' => (new DateTime('today'))->format('Y-m-d'),
                        'message' => "Date d'arrive ne doit pas etre avant la date aujoudhui"
                    ]),
                ],


            ])
            ->add("CheckOutDate", DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Date de depart ne peut pas etre vide"]),
                    new GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[CheckInDate].data',
                        'message' => "Date de depart doit etre apres date d'arrivée"
                    ]),
                ]
            ])

            ->add("NoAdult", ChoiceType::class, [
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
                'placeholder' => "Nombre des Adults",
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez preciser le nombre des personnes"])
                ]
            ])
            ->add("NoEnfant", ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ],
                'placeholder' => "Nombre des Enfants",
                'required' => false
            ])
            ->add("CodePromo", TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Code Promotionnal"
                ],
                'required' => false
            ])
            ->add("SpecialDemande", TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Demande Speciale"
                ],
                'required' => false
            ]);
        //---------------------------------------------------- NEED to check this in case of modification

        // });

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        //     $form = $event->getForm();


        //     $reservation = $event->getData();
        //     $checkin = $reservation->getCheckInDate();

        //     if ($reservation->getId() != null) {
        //         $form->add('arrivalDate', DateType::class, [
        //             'widget' => 'single_text',
        //             'label' => false,
        //             'placeholder' => "Date d'arrivée",
        //             'required' => false,
        //             'constraints' => [
        //                 new NotBlank(['message' => "Date d'arrivée ne peut pas etre vide"]),
        //                 new GreaterThanOrEqual([
        //                     'value' => (new DateTime('today'))->format('Y-m-d'),
        //                     'message' => "Date d'arrive ne doit pas etre avant la date aujoudhui"
        //                 ]),
        //             ],
        //             'data' => $checkin

        //         ])
        //             ->add("departureDate", DateType::class, [
        //                 'widget' => 'single_text',
        //                 'label' => false,
        //                 'required' => false,
        //                 'constraints' => [
        //                     new NotBlank(['message' => "Date de depart ne peut pas etre vide"]),
        //                     new GreaterThanOrEqual([
        //                         'propertyPath' => 'parent.all[arrivalDate].data',
        //                         'message' => "Date de depart doit etre apres date d'arrivée"
        //                     ]),
        //                 ],
        //                 'data' => $reservation->getCheckOutDate()
        //             ]);
        //     } else {
        //         $form->add('arrivalDate', DateType::class, [
        //             'widget' => 'single_text',
        //             'label' => false,
        //             'placeholder' => "Date d'arrivée",
        //             'required' => false,
        //             'constraints' => [
        //                 new NotBlank(['message' => "Date d'arrivée ne peut pas etre vide"]),
        //                 new GreaterThanOrEqual([
        //                     'value' => (new DateTime('today'))->format('Y-m-d'),
        //                     'message' => "Date d'arrive ne doit pas etre avant la date aujoudhui"
        //                 ]),
        //             ],


        //         ])
        //             ->add("departureDate", DateType::class, [
        //                 'widget' => 'single_text',
        //                 'label' => false,
        //                 'required' => false,
        //                 'constraints' => [
        //                     new NotBlank(['message' => "Date de depart ne peut pas etre vide"]),
        //                     new GreaterThanOrEqual([
        //                         'propertyPath' => 'parent.all[arrivalDate].data',
        //                         'message' => "Date de depart doit etre apres date d'arrivée"
        //                     ]),
        //                 ]
        //             ]);
        //     }



        //     // if ($reservation->getId() === null) {
        //     //     $form->add('category', EntityType::class, [
        //     //         'label' => 'Category de produit',
        //     //         'placeholder' => '--Choisir une category--',
        //     //         'class' => Category::class,
        //     //         'choice_label' => function (Category $category) {
        //     //             return strtoupper($category->getName());
        //     //         }
        //     //     ]);
        //     // }


        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
