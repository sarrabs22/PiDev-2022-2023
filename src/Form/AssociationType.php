<?php

namespace App\Form;

use App\Entity\Association;
use App\Entity\Categorie;
use App\Entity\CategorieAssociation;
use Captcha;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom' )
            ->add('numero')
            ->add('mail')
            ->add('adresse')
            /* ->add('Image', FileType::class ,[
                'data_class' => null,

                'label' => 'Logo' ,
                'attr' => ['placeholder' => 'image.jpg',
                'class'=>"form-control-file"]
                ]) */
                ->add('Image', FileType::class, [
                    'data_class' => null,
                    'label' => 'Logo',
                    'attr' => [
                        'placeholder' => 'image.jpg',
                        'class' => "form-control-file"
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\File([
                           
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/gif'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image file (jpeg, jpg, png, gif)',
                        ])
                    ]
                ])


            ->add('CodePostal')
            ->add('ville')
           
            ->add('categorie', EntityType::class, [
                'class' => CategorieAssociation::class,
                'choice_label' => 'nom'
            ])
            
           /*  ->add('captcha', CaptchaType::class,[
            'attr' => [
               
                'class' => "form-control"
            ],
            ]
        ); */
            
            ;

          

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
