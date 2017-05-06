<?php

namespace BookShopBundle\Form;

use BookShopBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name',TextType::class)
        ->add('published',ChoiceType::class,
            [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Published',
                'required' => true,
                'choices_as_values' => true
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class'=>Category::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'book_shop_bundle_category';
    }
}
