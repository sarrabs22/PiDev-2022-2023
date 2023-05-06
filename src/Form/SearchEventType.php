<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\CategorieEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SearchEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_event' , TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'required' => false,
            ] )
            ->add('localisation' ,TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Adresse',
                ],
                'required' => false,
            ] )
            ->add('categorie' ,EntityType::class, [
                'label' => ' ',
               
                'class' => CategorieEvent::class ,
                'required' => false,
                'choice_label'=>'nom_categ_event'
            ] )
            
            ->add('search', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class ,
            'method' => 'GET',
            'csrf_protection' => false,
        ]
        );
    }
}
