<?php

namespace App\Form;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, [
                'disabled' => true
            ])
            ->add('firstName', TextType::class, [
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'required' => true
            ])
            ->add('city', TextType::class, [
                'required' => true
            ])
            ->add('address', TextType::class, [
                'required' => true
            ])
            ->add('phone', TextType::class, [
                'required' => true
            ])
            ->add('nickName', TextType::class)
            ->add('www', UrlType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
}
