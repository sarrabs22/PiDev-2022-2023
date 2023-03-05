<?php

namespace App\Form;

use App\Entity\CategoryD;
use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NameD', TextType::class, [
                'attr' => [],
                'label' => 'Name'
            ])
            ->add('quantite', TextType::class, [
                'attr' => [],
                'label' => 'Quantite (Kg,L,P)'
            ])
            ->add('Description', TextareaType::class, [
                'attr' => [],
            ])
            ->add('Localisation')
            ->add('Image', FileType::class, [
                'data_class' => null,
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'image.jpg',
                    'class' => "form-control-file"
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
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
            ->add('email', EmailType::class)
            ->add('Numero')
            ->add('categoryD', EntityType::class, [
                'class' => CategoryD::class,
                'choice_label' => 'NameCa'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
