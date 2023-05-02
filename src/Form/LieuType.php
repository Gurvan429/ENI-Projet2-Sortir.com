<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez une ville'])
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom du lieu'
                ]
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'placeholder' => '39 rue de la bourgeonniere'
                ]
            ])
            ->add('latitude', NumberType::class, [
                'html5' => true,
                'attr' => ['step' => '0.000001',
                    'placeholder' => '49.750871',
                    ], // affiche jusqu'à 6 chiffres après la virgule
            ])
            ->add('longitude', NumberType::class, [
                'html5' => true,

                'attr' => ['step' => '0.000001',
                    'placeholder' => '3.871298',
                    ], // affiche jusqu'à 6 chiffres après la virgule
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}