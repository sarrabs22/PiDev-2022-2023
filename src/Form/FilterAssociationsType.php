<?php

namespace App\Form;

use App\Entity\Categorie;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class FilterAssociationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categories = $options['categories'];
        
        $builder
            ->add('categorie', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => function (Categorie $category) {
                    return $category->getNom();
                },
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['categories']);
    }
}