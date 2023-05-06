<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Mime\MimeTypes as MimeMimeTypes;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\All;
// use Symfony\Component\Validator\Constraints\MimeTypes;
use Symfony\Component\Mime\MimeTypes;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('Description')
            ->add('Image', FileType::class, [
                'data_class' => null,
                'mapped' => 'false',
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'placeholder' => 'image.jpg',
                    'class' => "form-control-file"
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/jpg',
                            'image/avif'
                        ],
                        'mimeTypesMessage' => 'Veuillez ajouter un type d image valide  (jpeg, png, gif, jpg, avif)',
                    ])
                ],
            ])
            
            ->add('adresse')
            #->add('Categorie', ChoiceType::class, [
            #'choices' => [
            #   'Option 1' => 'produits recyclables',
            #  'Option 2' => 'habillement',
            # 'Option 3' => 'immobilier',
            #'Option 4' => 'appareils électroniques',
            #'Option 5' => 'produits artisanaux',
            #],
            #]);
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'Nom'

            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
