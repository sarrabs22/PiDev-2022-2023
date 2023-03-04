<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class StarRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('one_star', ChoiceType::class, [
                'choices' => [
                    '1 Star' => 1,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'One Star',
                'required' => true,
            ])
            ->add('two_star', ChoiceType::class, [
                'choices' => [
                    '2 Stars' => 2,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Two Stars',
                'required' => true,
            ])
            ->add('three_star', ChoiceType::class, [
                'choices' => [
                    '3 Stars' => 3,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Three Stars',
                'required' => true,
            ])
            ->add('four_star', ChoiceType::class, [
                'choices' => [
                    '4 Stars' => 4,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Four Stars',
                'required' => true,
            ])
            ->add('five_star', ChoiceType::class, [
                'choices' => [
                    '5 Stars' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Five Stars',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
            ]);
    }
}
