<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{

    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findValid()
    {
        $unMoisAvant = new \DateTime('-1 month');

        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.dateHeureDebut >= :unMoisAvant')
            ->setParameter('unMoisAvant', $unMoisAvant)
            ->getQuery();

        return $qb->execute();
    }

    public function findByFiltres($filtres)
    {
        // Création d'une requête pour récupérer les sorties filtrées
        $user = $this->security->getUser();
        $unMoisAvant = new \DateTime('-1 month');

        $qb = $this->createQueryBuilder('s')
        ->andWhere('s.dateHeureDebut >= :unMoisAvant')
        ->setParameter('unMoisAvant', $unMoisAvant);

        // Application des filtres
        if ($filtres['campus']) {
            $qb->andWhere('s.campus = :campus')
                ->setParameter('campus', $filtres['campus']);
        }
        if ($filtres['nom']) {
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', "%{$filtres['nom']}%");
        }
        if ($filtres['dateDebut'] && $filtres['dateFin']) {
            $qb->andWhere('s.dateHeureDebut >= :dateDebut')
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateDebut', $filtres['dateDebut'])
                ->setParameter('dateFin', $filtres['dateFin']);
        }
        //Si je suis l'organisateur
        if ($filtres['orga']) {
            $qb->setParameter('user', $user);
            $qb->andWhere('s.createur = :user');
        }
        //Si je suis inscrit
        if ($filtres['inscrit']) {
            $qb->setParameter('user', $user);
            $qb->andWhere(':user MEMBER OF s.participants');
        }
        //Si je suis pas inscrit
        if ($filtres['nonInscrit']) {
            $qb->setParameter('user', $user);
            $qb->andWhere(':user NOT MEMBER OF s.participants');
        }
        //Si la sortie est passée
        if ($filtres['passes']) {

            $qb->join('s.etat', 'etat')
                ->andWhere('etat.slug = 5');

        }
        // Exécution de la requête
        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }


//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
