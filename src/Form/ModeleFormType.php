<?php

namespace App\Form;

use App\Entity\Modele;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class ModeleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 400]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La description ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('agence', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 100]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'L\'agence ne peut contenir que des lettres.',
                    ]),
                    
                ],
            ])
            ->add('couleur_yeux', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La couleur de yeux ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('couleur_cheveux', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La couleur des cheveux ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('taille', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['max' => 10]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                    
                ],
            ])
            ->add('taille_hanches', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['max' => 10]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                ],
            ])
            ->add('tour_de_poitrine', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['max' => 10]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                ],
            ])
            ->add('pointure', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['max' => 10]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                ],
            ])
            ->add('poids', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['max' => 10]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                ],
            ])
            ->add('type_ethnique', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Type ethnique ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('signes_particuliers', TextType::class, [
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Signes particuliers ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add("Modifier", SubmitType::class, ['attr' => ['class' => 'index_button']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modele::class,
        ]);
    }
}
