<?php

namespace BookShopBundle\Form;

use BookShopBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('username',TextType::class,['required'=>false])
            ->add('email',TextType::class,['required'=>false])
            ->add('password_raw',RepeatedType::class,[

                'type'=>PasswordType::class,
                'first_options'=>[
                    'required'=>false,
                    'label'=>'Password'
                ],
                'second_options'=>[
                    'required'=>false,
                    'label'=>'Repeat password'
                ]
            ])
        ->add('Submit',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            [
                'data_class'=>User::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'book_shop_bundle_user_type';
    }
}
