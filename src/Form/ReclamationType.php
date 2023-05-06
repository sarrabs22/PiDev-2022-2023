<?php

namespace App\Form;
use App\Entity\CategorieRec;
use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('MotifDeReclamation',ChoiceType::class, [
                'label' => 'Motif De Reclamation',
                'attr' => [
                    'class' => 'form-control rolling-list',
                ],
                'choices' => [
                    'Problème de sécurité' => 'Problème de sécurité',
                    'Problème d interface utilisateur' => 'Problème d interface utilisateur',
                    'Problème de conformité' => 'Problème de conformité',
                    'Problème de suivi' => 'Problème de suivi',
                   
                ],
                
            ])
            ->add('NumTelephone')
            ->add('Image', FileType::class ,[
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
                        'mimeTypesMessage' => 'Please upload a valid image file (jpeg, png, gif)',
                    ])
                ]
            ])
            
           
            ->add('categorieRec' , EntityType::class,[
                'class' => CategorieRec::class ,
                'choice_label'=>'Nom'
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
