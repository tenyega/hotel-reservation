<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre Nom Complete"
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez indiquer votre nom svp"])
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre Feedback"
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez indiquer votre feedback svp"])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre email adresse"
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => "Veuillez indiquer votre email svp"]),
                    new Email(['message' => "Veuillez indiquer un valide adresse email svp"])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
