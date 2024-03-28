<?php

namespace App\Form;

use App\Entity\Modele;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reseaux_sociaux')
            ->add('agence')
            ->add('description')
            ->add('signes_particuliers')
            ->add('taille')
            ->add('taille_hanches')
            ->add('tour_de_poitrine')
            ->add('pointure')
            ->add('poids')
            ->add('couleur_yeux')
            ->add('couleur_cheveux')
            ->add('type_ethnique')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modele::class,
        ]);
    }
}
