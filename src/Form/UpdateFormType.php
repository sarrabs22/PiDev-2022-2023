<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
                
                    
            
            ->add('nom',TextType::class,[
                
                'attr' => [
                    'class' => 'form-control'
                ]
                
                ])
            ->add('prenom',TextType::class,[
                
                'attr' => [
                    'class' => 'form-control'
                ]
                
                ])
                ->add('NumTelephone',TextType::class,[
                
                    'attr' => [
                        'class' => 'form-control'
                    ]
                    
                    ])
            /* ->add('image',FileType::class, [
                'data_class' => null,
                'label' => '.',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'inserer une image'
                ],
                'mapped'=>'false',
                
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Veuillez saisir une image valide (jpeg, png, gif)',
                    ])
                ],
            ]) */
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
