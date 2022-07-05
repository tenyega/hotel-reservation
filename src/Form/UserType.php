<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Prénom"
                ],
                'constraints' => [
                    new NotBlank(['message' => "Prénom ne peut pas etre vide"])
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom"
                ],
                'constraints' => [
                    new NotBlank(['message' => "Nom ne peut pas etre vide"])
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Numero pour vous joindre"
                ],
                'constraints' => [
                    new NotBlank(['message' => "Numéro de telephone ne peut pas etre vide"])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Email addresse "
                ],
                'constraints' => [
                    new NotBlank(['message' => "Email ne peut pas etre vide"])
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Mot de Passe..."
                ],
                'constraints' => [
                    new NotBlank(['message' => "Mot de passe ne peut pas etre vide"])
                ],
            ])

            ->add('address', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Adresse pour la livraison"
                ],
                'constraints' => [
                    new NotBlank(['message' => "Adresse ne peut pas etre vide"])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
