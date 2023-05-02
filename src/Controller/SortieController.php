<?php

namespace App\Controller;


use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortiesType;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use ContainerIEox3js\getCampusRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Etat;


class SortieController extends AbstractController
{
    private $campusRepository;

    public function __construct(CampusRepository $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $sortie = new Sortie();

        $sortieForm = $this->createForm(SortiesType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $sortie = $sortieForm->getData();

            if ($sortieForm->getClickedButton() && 'create' == $sortieForm->getClickedButton()->getName()) {
                $etatLibelle = 2;
            } else {
                $etatLibelle = 1;
            }

            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['slug' => $etatLibelle]);
            $sortie->setEtat($etat);

            $participant = $this->getUser();

            $sortie->setCreateur($participant);

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sorties', ['id' => $sortie->getId()]);
        }


        return $this->render('main/sortie/createS.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);

    }

    /**
     * @Route("/sorties", name="sorties")
     */

    public function sorties(Request $request, SortieRepository $sortieRepository): Response
    {
        // Récupération de toutes les sorties
        $sorties = $sortieRepository->findValid();
        $user = $this->getUser();
        // Création du formulaire de filtre
        $form = $this->createFormBuilder()
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choices' => $this->campusRepository->findAll(),
                'choice_label' => 'nom',
                'placeholder' => 'Choississez un Campus',
                'required' => false,
            ])
            ->add('nom', TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'Rechercher'
                ],
                'required' => false,
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Entre',
                'widget' => 'single_text', // Affichage du champ sous forme d'un seul champ texte
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'et',
                'widget' => 'single_text', // Affichage du champ sous forme d'un seul champ texte
                'attr' => [
                    'class' => 'form-control-sm'

                ],
                'required' => false,
            ])
            ->add('orga', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis l\'inscrit/e',
                'required' => false,
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('passes', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-outline-primary btn-sm mx-auto d-block'
                ],
            ])
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $filtres = $form->getData();
            $sorties = $sortieRepository->findByFiltres($filtres);
        }

        return $this->render('main/sortie/afficherSortie.html.twig', [
            'form' => $form->createView(),
            'sorties' => $sorties,
        ]);

    }

    /**
     * @Route("/sortie/{id}", name="app_sortie_details")
     */
    public function details(Request $request, SortieRepository $sortieRepository, ParticipantRepository $participantRepository, int $id): Response
    {

        $sortie = $sortieRepository->find($id);

        return $this->render('main/sortie/details.html.twig', [
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/sortie/{id}/modifier", name="modifier")
     */
    function modifierSortie(Request $request, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer l'entité Sortie à partir du repository
        $sortie = $sortieRepository->find($id); // Remplacez $id par l'identifiant de la sortie à modifier

        // Créer le formulaire en utilisant l'entité Sortie
        $form = $this->createFormBuilder($sortie)
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
            ->add('modifier', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-outline-success btn-sm'
                ],
            ])
            ->add('annuler', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'btn btn-outline-secondary btn-sm d-block'
                ],
            ])
        ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page d'accueil ou vers une autre page de votre choix
            return $this->redirectToRoute('sorties');
        }

        return $this->render('main/sortie/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/{idSortie}/inscrire", name="inscrire_participant")
     */
    public function inscrireParticipant($idSortie): Response
    {
        // Récupérer la sortie avec l'ID donné
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($idSortie);

        // Vérifier que la sortie existe
        if (!$sortie) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        // Récupérer le participant avec l'ID donné
        $participant = $this->getUser();

        // Vérifier que le participant existe
        if (!$participant) {
            throw $this->createNotFoundException('Participant introuvable');
        }

        // Inscrire le participant à la liste de participants de la sortie
        $sortie->addParticipant($participant);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sorties');


    }

    /**
     * @Route("/sortie/{idSortie}/remove", name="remove_participant")
     */
    public function removeParticipant($idSortie): Response
    {
        // Récupérer la sortie avec l'ID donné
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($idSortie);

        // Vérifier que la sortie existe
        if (!$sortie) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        // Récupérer le participant avec l'ID donné
        $participant = $this->getUser();

        // Vérifier que le participant existe
        if (!$participant) {
            throw $this->createNotFoundException('Participant introuvable');
        }

        // Inscrire le participant à la liste de participants de la sortie
        $sortie->removeParticipant($participant);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();


        return $this->redirectToRoute('sorties');
    }

    /**
     * @Route("/sortie/{idSortie}/publier", name="publier_sortie")
     */

    public function publier($idSortie, EntityManagerInterface $entityManager): Response
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($idSortie);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        $etat = $entityManager->getRepository(Etat::class)->findOneBy(['slug' => 2]);


        $sortie->setEtat($etat);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();


        return $this->redirectToRoute('sorties');

    }

    /**
     * @Route("/sortie/{idSortie}/annuler", name="annuler_sortie")
     */

    public function annuler($idSortie, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($idSortie);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        $etat = $entityManager->getRepository(Etat::class)->findOneBy(['slug' => 6]);

        $form = $this->createFormBuilder()
            ->add('motif', TextareaType::class, [
                'label' => 'Motif d\'annulation :',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $motif = $form->getData()['motif'];

            $sortie->setMotifAnnulation($motif);

            $sortie->setEtat($etat);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();


            return $this->redirectToRoute('sorties');
        }
        return $this->render('main/sortie/annuler.html.twig', [
            'form' => $form->createView(),
            'sortie' => $sortie,
        ]);

    }

}