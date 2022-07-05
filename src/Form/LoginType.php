<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Adresse email"
                ],
                'constraints' => [
                    new NotBlank(['message' => "Prénom ne peut pas etre vide"])
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Mot de Passe...'
                ],
                'constraints' => [
                    new NotBlank(['message' => "Prénom ne peut pas etre vide"])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
