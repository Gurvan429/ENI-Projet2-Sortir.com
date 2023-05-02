<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Participant;
use Monolog\DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;



    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //Création des différents Etat

        $etat1 = new Etat();
        $etat1->setLibelle('En création');
        $etat1->setSlug(1);
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $etat2->setSlug(2);
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $etat3->setSlug(3);
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('Activité en cours');
        $etat4->setSlug(4);
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Passée');
        $etat5->setSlug(5);
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $etat6->setSlug(6);
        $manager->persist($etat6);

        $campus1 = new Campus();
        $campus1->setNom('En ligne');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('Nantes');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom('Bordeaux');
        $manager->persist($campus3);

        $campus4 = new Campus();
        $campus4->setNom('Rennes');
        $manager->persist($campus4);

        $ville1 = new Ville();
        $ville1->setNom('Nantes');
        $ville1->setCodePostal('44000');
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Pornichet');
        $ville2->setCodePostal('44380');
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Montcuq');
        $ville3->setCodePostal('46800');
        $manager->persist($ville3);

        $ville4 = new Ville();
        $ville4->setNom('Paris');
        $ville4->setCodePostal('75001');
        $manager->persist($ville4);

        $ville5 = new Ville();
        $ville5->setNom('Rennes');
        $ville5->setCodePostal('35000');
        $manager->persist($ville5);

        $ville6 = new Ville();
        $ville6->setNom('Marseille');
        $ville6->setCodePostal('13005');
        $manager->persist($ville6);

        $ville7 = new Ville();
        $ville7->setNom('Lille');
        $ville7->setCodePostal('59000');
        $manager->persist($ville7);

        $lieu1 = new Lieu();
        $lieu1->setNom('Commerce');
        $lieu1->setVille($ville1);
        $lieu1->setRue('Cours des 50 otages');
        $lieu1->setLatitude(47.214243);
        $lieu1->setLongitude(-1.555429);

        $lieu2 = new Lieu();
        $lieu2->setNom('Tour Montparnasse');
        $lieu2->setVille($ville4);
        $lieu2->setRue('33 Av. du Maine');
        $lieu2->setLatitude(48.84211428708443);
        $lieu2->setLongitude(2.321944019023327);

        $lieu3 = new Lieu();
        $lieu3->setNom('Plage de la Bonne-Source');
        $lieu3->setVille($ville2);
        $lieu3->setRue('Route de l\'océan');
        $lieu3->setLatitude(47.236127);
        $lieu3->setLongitude(-2.297012);

        $lieu4 = new Lieu();
        $lieu4->setNom('Plage de la Bonne-Mère');
        $lieu4->setVille($ville6);
        $lieu4->setRue('Rue de l\'or');
        $lieu4->setLatitude(47.236127);
        $lieu4->setLongitude(-2.297012);

        $participant1 = new Participant();
        $participant1->setEmail('Brunet@mail.com');
        $participant1->setRoles(["ROLE_ADMIN"]);
        $participant1->setPassword($this->passwordHasher->hashPassword($participant1, 'azerty'));
        $participant1->setNom('Brunet');
        $participant1->setPrenom('Clément');
        $participant1->setPseudo('Clem86');
        $participant1->setTelephone('0102030408');
        $participant1->setActif('true');
        $participant1->setCampus($campus1);
        $manager->persist($participant1);

        $participant2 = new Participant();
        $participant2->setEmail('jochum@mail.com');
        $participant2->setRoles(["ROLE_ADMIN"]);
        $participant2->setPassword($this->passwordHasher->hashPassword($participant2, 'azerty'));
        $participant2->setPseudo('Gurvan29');
        $participant2->setNom('Jochum');
        $participant2->setPrenom('Gurvan');
        $participant2->setTelephone('0102030407');
        $participant2->setActif('true');
        $participant2->setCampus($campus1);
        $manager->persist($participant2);

        $participant3 = new Participant();
        $participant3->setPseudo('JulVass');
        $participant3->setNom('Vassivière');
        $participant3->setPrenom('Julien');
        $participant3->setTelephone('0102030406');
        $participant3->setEmail('Vassiviere@mail.com');
        $participant3->setPassword($this->passwordHasher->hashPassword($participant3, 'azerty'));
        $participant3->setActif('true');
        $participant3->setRoles(["ROLE_ADMIN"]);
        $participant3->setCampus($campus1);
        $manager->persist($participant3);

        $participant4 = new Participant();
        $participant4->setPseudo('IMath');
        $participant4->setNom('Leleu');
        $participant4->setPrenom('Mathieu');
        $participant4->setTelephone('0102030405');
        $participant4->setEmail('Leleu@mail.com');
        $participant4->setPassword($this->passwordHasher->hashPassword($participant4, 'azerty'));
        $participant4->setActif('true');
        $participant4->setRoles(["ROLE_ADMIN"]);
        $participant4->setCampus($campus1);
        $manager->persist($participant4);

        $participant5 = new Participant();
        $participant5->setPseudo('eleve1');
        $participant5->setNom('Jules');
        $participant5->setPrenom('Mésar');
        $participant5->setTelephone('0102030405');
        $participant5->setEmail('eleve1@mail.com');
        $participant5->setPassword($this->passwordHasher->hashPassword($participant4, 'azerty'));
        $participant5->setActif('true');
        $participant5->setRoles(["ROLE_USER"]);
        $participant5->setCampus($campus1);
        $manager->persist($participant5);

        $participant6 = new Participant();
        $participant6->setPseudo('eleve2');
        $participant6->setNom('Sébatien');
        $participant6->setPrenom('Bob');
        $participant6->setTelephone('0102030405');
        $participant6->setEmail('eleve2@mail.com');
        $participant6->setPassword($this->passwordHasher->hashPassword($participant4, 'azerty'));
        $participant6->setActif('true');
        $participant6->setRoles(["ROLE_USER"]);
        $participant6->setCampus($campus4);
        $manager->persist($participant5);

        $sortie = new Sortie();
        $sortie->setNom('test');
        $sortie->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-15'));
        $sortie->setDuree(10860);
        $sortie->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-17'));
        $sortie->setNbInscriptionsMax(10);
        $sortie->setInfosSortie('test');
        $sortie->setEtat($etat1);
        $sortie->addParticipant($participant1);
        $sortie->addParticipant($participant2);
        $sortie->addParticipant($participant3);
        $sortie->addParticipant($participant4);
        $sortie->setCreateur($participant2);
        $sortie->addParticipant($participant1);
        $sortie->addParticipant($participant2);
        $sortie->addParticipant($participant3);
        $sortie->addParticipant($participant4);
        $sortie->setCampus($campus1);
        $sortie->setLieu($lieu2);
        $manager->persist($sortie);

        $sortie2 = new Sortie();
        $sortie2->setNom('Sortie de fou');
        $sortie2->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-02-15'));
        $sortie2->setDuree(7200);
        $sortie2->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-02-17'));
        $sortie2->setNbInscriptionsMax(8);
        $sortie2->setInfosSortie('Une sortie que tu vas adorer !');
        $sortie2->setEtat($etat5);
        $sortie2->setCreateur($participant2);
        $sortie2->addParticipant($participant1);
        $sortie2->addParticipant($participant2);
        $sortie2->addParticipant($participant3);
        $sortie2->addParticipant($participant4);
        $sortie2->setCampus($campus2);
        $sortie2->setLieu($lieu2);
        $manager->persist($sortie2);


        $sortie3 = new Sortie();
        $sortie3->setNom('Cinéma');
        $sortie3->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-28'));
        $sortie3->setDuree(10800);
        $sortie3->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-24'));
        $sortie3->setNbInscriptionsMax(3);
        $sortie3->setInfosSortie('Film mario Bros au cinéma');
        $sortie3->setEtat($etat2);
        $sortie3->setCreateur($participant4);
        $sortie3->addParticipant($participant1);
        $sortie3->addParticipant($participant4);
        $sortie3->setCampus($campus2);
        $sortie3->setLieu($lieu1);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom('Balade à Vélo');
        $sortie4->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-29'));
        $sortie4->setDuree(7200);
        $sortie4->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-24'));
        $sortie4->setNbInscriptionsMax(3);
        $sortie4->setInfosSortie('Tour de vélo sur la côte');
        $sortie4->setEtat($etat2);
        $sortie4->setCreateur($participant1);
        $sortie4->addParticipant($participant1);
        $sortie4->addParticipant($participant4);
        $sortie4->setCampus($campus2);
        $sortie4->setLieu($lieu3);
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setNom('Créer un site de sorties');
        $sortie5->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-19'));
        $sortie5->setDuree(10800);
        $sortie5->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-04-21'));
        $sortie5->setNbInscriptionsMax(15);
        $sortie5->setInfosSortie('Apprendre à faire un site de sorties pour des étudiants');
        $sortie5->setEtat($etat4);
        $sortie5->setCreateur($participant2);
        $sortie5->addParticipant($participant2);
        $sortie5->addParticipant($participant4);
        $sortie5->addParticipant($participant1);
        $sortie5->addParticipant($participant3);
        $sortie5->setCampus($campus1);
        $sortie5->setLieu($lieu1);
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setNom('Parc Astérix');
        $sortie6->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-05-15'));
        $sortie6->setDuree(28800);
        $sortie6->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-05-02'));
        $sortie6->setNbInscriptionsMax(15);
        $sortie6->setInfosSortie('Rdv à 8h le jour de la sortie pour prendre le car. Amenez votre goûter !');
        $sortie6->setEtat($etat2);
        $sortie6->setCreateur($participant3);
        $sortie6->addParticipant($participant1);
        $sortie6->addParticipant($participant2);
        $sortie6->addParticipant($participant3);
        $sortie6->addParticipant($participant4);
        $sortie6->setCampus($campus2);
        $sortie6->setLieu($lieu2);
        $manager->persist($sortie6);

        $sortie7 = new Sortie();
        $sortie7->setNom('Afterwork');
        $sortie7->setDateHeureDebut(DateTimeImmutable::createFromFormat('Y-m-d', '2023-05-24'));
        $sortie7->setDuree(14400);
        $sortie7->setDatelimiteInscription(DateTimeImmutable::createFromFormat('Y-m-d', '2023-05-09'));
        $sortie7->setNbInscriptionsMax(30);
        $sortie7->setInfosSortie('Surprise ! Le but est de se détendre et de s\'amuser');
        $sortie7->setEtat($etat2);
        $sortie7->setCreateur($participant3);
        $sortie7->addParticipant($participant1);
        $sortie7->addParticipant($participant2);
        $sortie7->addParticipant($participant3);
        $sortie7->addParticipant($participant4);
        $sortie7->setCampus($campus1);
        $sortie7->setLieu($lieu1);
        $manager->persist($sortie7);
        
        




        $manager->flush();

    }
}
