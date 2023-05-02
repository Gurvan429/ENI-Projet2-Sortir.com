<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', EmailType::class, [
                'label' => 'Adresse e-mail ou pseudo',
                'attr' => [
                    'placeholder' => 'Votre adresse e-mail ou pseudo'
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ],
            ])
        // Fonction se souvenir de moi !
        ->add('rememberMe', CheckboxType::class, [
            'label' => 'Se souvenir de moi',
            'required' => false,
        ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // configure les options de validation !!
        ]);
    }
}
