<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', null, [
            'constraints' => [
                new Email([
                    'message' => 'L\'email "{{ value }}" n\'est pas valide.',
                ]),
            ],
        ])
        ->add('ville', null, [
            'constraints' => [
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'La ville ne peut contenir que des lettres.',
                ]),
            ],
        ])
        ->add('code_postal', null, [
            'constraints' => [
                new Length([
                    'min' => 5,
                    'max' => 5,
                    'exactMessage' => 'Le code postal doit contenir exactement {{ limit }} chiffres.',
                ]),
            ],
        ])
        ->add('pays', null, [
            'constraints' => [
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'Le pays ne peut contenir que des lettres.',
                ]),
            ],
        ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Modèle' => 'ROLE_MODELE',
                    'Photographe' => 'ROLE_PHOTOGRAPHE',
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Acceptez les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez les conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un Mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre Mot de passe doit contenir minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Votre Mot de passe doit contenir maximum {{ limit }} caractères',
                    ]),
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
