<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', FileType::class, [
                'required' => false,
                'label' => 'Fichier photo en liens avec le produit',
                'attr' => [
                    'onChange' => 'loadFile(event)'

                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Fichier trop volumineux, {{ size }} maximum',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => "Formats autorisés: 'image/jpg', 'image/jpeg', 'image/png', 'image/webp' "

                    ])
                ]
            ])
            // ->add('destination')
            ->add('destination', ChoiceType::class, [
                'choices' => [
                    'Section 1 - page d\'Accueil BOOK' => 'section1',
                    'Section 2 - page d\'Accueil BOOK' => 'section2',
                    'Section 3 - page d\'Accueil BOOK' => 'section3',
                    'Section 4 - page d\'Accueil BOOK' => 'section4',
                    
                ]
            ])
            ->add('description')
            ->add('Ajouter', SubmitType::class, ['attr' => ['class' => 'index_button']]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
