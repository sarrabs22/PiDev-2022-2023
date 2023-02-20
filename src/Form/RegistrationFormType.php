<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\EqualTo;

class RegistrationFormType extends AbstractType
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
            ->add('email',TextType::class,[
                
                'attr' => [
                    'class' => 'form-control'
                ]
                
                ])
            ->add('NumTelephone',TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('image', FileType::class, [
            'label' => 'Photo de Profil',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'inserer une image'
            ],
            
            
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
        ])
            ->add('type', ChoiceType::class, [
                'label' => 'type',
                'attr' => [
                    'class' => 'form-control rolling-list',
                ],
                'choices' => [
                    'Donneur' => 'Donneur',
                    'Receveur' => 'Receveur',
                   
                ],
                
            ])
            
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'text-blue'],
                'invalid_message' => 'les mot de passes ne sont pas identiques',
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['placeholder' => 'Veuillez saisir votre mot de passe', 'class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe', 'class' => 'mt-1 form-control']]
            ])
            /* ->add('ConfirmezVotreMotDePasse', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                   
                ],
            ]) */
            /* ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}
