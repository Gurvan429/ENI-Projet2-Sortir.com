<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;
use App\Form\UserType;

class ProfilControler extends AbstractController
{

    /**
     * @Route("/edit", name="edit")
     */

    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, ParticipantRepository $userRepository)
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $form = $this->createForm(UserType::class, $user); // Crée le formulaire avec l'entité utilisateur
        $form->handleRequest($request); // Traite la soumission du formulaire

        if ($form->isSubmitted() && $form->isValid()) {

            $userConnected = $this->getUser();
            if ($userRepository->findBy(['email' => $user->getEmail()])) {
                if ($userConnected && $userConnected->getEmail() === $user->getEmail()) {
                    // L'email appartient à l'utilisateur connecté, donc on ignore la contrainte unique.
                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('sorties');
                } else {
                    // L'email est déjà utilisé par un autre compte.
                    $errorMessage = "Il y a déjà un compte utilisant cette adresse e-mail.";
                    $form->get('email')->addError(new FormError($errorMessage));
                    return $this->render('registration/register.html.twig', [
                        'registrationForm' => $form->createView(),
                    ]);
                }
            } else {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                // L'email est unique, on peut créer le compte.
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('sorties');
            }



//            $entityManager->persist($user); // Enregistre l'utilisateur dans la base de données
//            $entityManager->flush(); // Exécute les requêtes SQL pour mettre à jour l'utilisateur
//            return $this->redirectToRoute('sorties'); // Redirige l'utilisateur vers la page de profil mise à jour
        }

        return $this->render('main/profile/profil.html.twig', [
            'form' => $form->createView(), // Transmet le formulaire à la vue Twig
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher_profile")
     */
     public function afficher(Request $request, ParticipantRepository $participantRepository, int $id): Response
    {

        $participant = $participantRepository->find($id);

        return $this->render('main/profile/afficher.html.twig', [
            'participant'=>$participant
        ]);
    }
}
