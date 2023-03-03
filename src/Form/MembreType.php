<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mail')
            ->add('age')
            ->add('passions')

            /* ->add('ajaxString', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field', 'value' => $secretString]
            ]) */

           /*  ->add('YesExperience', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field', 'value' => 'YesExperience']
                ])
 */

          ->add('YesExperience', ChoiceType::class, [
                'choices' => [
                    '' => [
                        'No' => 'no',
                        'Yes' => 'yes',
                        
                    ],
                ]]) 
 
// ->add('pYesExperience', CheckboxType::class, [
//     'label_attr' => ['class' => 'switch-custom'],
// ])

            ->add('experience', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field', 'value' => 'YesExperience']
                ]
            
            )
            ->add('ClubEntendu')
            ->add('actions')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
