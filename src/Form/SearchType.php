<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('Description')


            ->add('adresse', TextType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Adresse',
                ],
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'label' => ' ',

                'class' => Categorie::class,
                'required' => false,
                'choice_label' => 'nom'
            ])

            ->add('Recherche', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
