<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;


class SortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : ', //Libellé du champ
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ', //Libellé du champ
                'html5' => true,
                'widget' => 'single_text', // Affichage du champ sous forme d'un seul champ texte
            ])
            ->add('duree', TimeType::class, [
                'label' => 'Durée : ', //Libellé du champ
                'input' => 'timestamp',
                'widget' => 'choice',
            ])
            ->add('datelimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription : ', //Libellé du champ
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places disponibles : ', //Libellé du champ
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos : ', //Libellé du champ
                'attr' => [
                   // 'rows' => 3, //Nombre de lignes du champ
                    'placeholder' => 'Saisissez votre texte...', //Texte du placeholder
                ]
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un campus',
                'required' => false,
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => function (Lieu $lieu) {
                    return $lieu->getNom() . ' - ' . $lieu->getVille()->getNom() . ' ' . $lieu->getVille()->getCodePostal();
                },
                'placeholder' => 'Choisissez un lieu',
                'query_builder' => function (LieuRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->leftJoin('l.ville', 'v')
                        ->orderBy('v.nom', 'ASC');
                },
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-outline-primary btn-sm  d-block',
                    'onclick' => "return confirm('Es-tu sûr de vouloir publier cette sortie ?')"
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregister',
                'attr' => [
                    'class' => 'btn btn-outline-success btn-sm d-block',
                    'onclick' => "return confirm('Es-tu sûr de vouloir enregistrer cette sortie ?')",
                ],
            ]);



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
