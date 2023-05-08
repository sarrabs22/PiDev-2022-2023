<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('age', TextType::class, [
                'label' => ' ' ])
            ->add('passions' , TextType::class, [
                'label' => ' ' ])

            /* ->add('ajaxString', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field', 'value' => $secretString]
            ]) */

           /*  ->add('YesExperience', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field', 'value' => 'YesExperience']
                ])
 */

/*  $builder->add('name', null, [
    'required'   => false,
    'empty_data' => 'John Doe',
]); */

     




        //   ->add('YesExperience', ChoiceType::class, [
        //         'choices' => [
        //             '' => [
        //                 'No' => 'no',
        //                 'Yes' => 'yes',
                        
        //             ],
        //         ]]) 
 
// ->add('pYesExperience', CheckboxType::class, [
//     'label_attr' => ['class' => 'switch-custom'],
// ])



->add('experience', TextType::class, [
    'label' => ' ' ,
    'required' => false,
])
            ->add('ClubEntendu', TextType::class, [
                'label' => ' ' ])
            ->add('actions', TextType::class, [
                'label' => ' ' ]);


              /*   ->add('captcha', CaptchaType::class,[
                    'attr' => [
                       
                        'class' => "form-control"
                    ],
                    ]
                ); */
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
