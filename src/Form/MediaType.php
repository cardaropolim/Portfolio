<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', FileType::class,[
                'required'=> false,
                'label'=>'Fichier photo en liens avec le produit',
                'attr'=>[
                    'onChange'=>'loadFile(event)'

                ],
                'constraints'=>[
                    new File([
                        'maxSize'=>'6000k', 
                        'maxSizeMessage'=>'Fichier trop volumineux, 6Mo maximum',
                        'mimeTypes'=>['image/jpg', 'image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage'=>"Formats autorisés: 'image/jpg', 'image/jpeg', 'image/png', 'image/webp' "

                    ])
                ]
            ])
            ->add('destination')
            ->add('description')
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
