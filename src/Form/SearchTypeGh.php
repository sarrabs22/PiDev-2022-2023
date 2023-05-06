<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CategorieRec;

class SearchTypeGh extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            
            ->add('etat', TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Etat',
                    'class'=>'form-control-plaintext', 
                    
                ],
                
                'required' => false,
            ])
            ->add('MotifDeReclamation', TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'MotifDeReclamation',
                    'class'=>'form-control-plaintext',
                ],
                'required' => false,
            ])
            ->add('categorieRec',EntityType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'categorieRec',
                    'class'=>'form-control-plaintext',
                ],
                'class' => CategorieRec::class ,
                'required' => false,
                'choice_label'=>'Nom'
            ])
            ->add('search', SubmitType::class, [
                'attr' => [
                    
                    'class'=>'btn btn-success',
                ],
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
