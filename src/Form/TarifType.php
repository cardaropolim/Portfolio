<?php

namespace App\Form;

use App\Entity\Tarifs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;


class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prestations', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Les prestations ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('prix', null, [
                'constraints' => [
                    new Length([
                        'max' => 10,
                        'exactMessage' => 'Les prix doivent contenir exactement {{ limit }} chiffres.',
                        
                    ]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit contenir uniquement des chiffres.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarifs::class,
        ]);
    }
}
