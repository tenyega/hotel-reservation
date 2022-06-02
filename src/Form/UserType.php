<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label' => "Prénom",
                'attr' => [
                    'placeholder' => "Prénom complete pour la livarison"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "nom complete pour la livarison"
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => "Un numero a vous joindre",
                'attr' => [
                    'placeholder' => 'Phone no'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'attr' => [
                    'placeholder' => "Email addresse "
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de Passe',
                'attr' => [
                    'placeholder' => 'Mot de Passe...'
                ]
            ])

            ->add('address', TextareaType::class, [
                'label' => 'Adresse Complete',
                'attr' => [
                    'placeholder' => 'adresse pour la livraison'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
