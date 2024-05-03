<?php

namespace App\Form;

use App\Entity\Photographe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class PhotographeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agence', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 100]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'L\'agence ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 400]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La descirption ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('signes_particuliers', null, [
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Les signes particuliers ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add("Modifier", SubmitType::class, ['attr' => ['class' => 'index_button']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photographe::class,
        ]);
    }
}
