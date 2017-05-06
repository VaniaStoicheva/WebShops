<?php

namespace BookShopBundle\Form;

use BookShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;



class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name',TextType::class,[
            'label'=>'The Book name:'
        ])
            ->add('description',TextareaType::class,[
                'label'=>'The Book Author:'
            ])
            ->add('quantity',NumberType::class)
            ->add('price',MoneyType::class,['currency'=>'BGN'])
            ->add('image_form', FileType::class,  [
                'data_class' => null


            ] )
            ->add('categories',EntityType::class, [
                'class' => 'BookShopBundle\Entity\Category',
                'multiple' => true


      ])
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
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BookShopBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bookshopbundle_product';
    }


}
