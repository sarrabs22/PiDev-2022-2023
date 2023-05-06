<?php

namespace App\Form;

use App\Entity\CategorieEvent;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_event' , TextType::class, [
            'label' => ' ',
            'attr' => [
                'placeholder' => 'Nom',
            ],
            'row_attr' => [
                'class' => '',
            ],
          ])

            ->add('date_debut')
            ->add('date_fin')
            ->add('localisation'  , TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Adresse',
                ],
                'row_attr' => [
                    'class' => '',
                ],
              ])
           
            ->add('Image_Event', FileType::class ,[
                'data_class' => null,
                'label' => 'Image' ,
                'attr' => ['placeholder' => 'image.jpg',
                'class'=>"form-control-file"],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'veuillez ajouter une image (jpeg, jpg, png, gif)',
                    ])
                ]

                ]) 
            ->add('categorie' , EntityType::class,[
                'class' => CategorieEvent::class ,
                'choice_label'=>'nom_categ_event'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
