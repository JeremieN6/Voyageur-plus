<?php

namespace App\Repository;

use App\Entity\Reponses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponses>
 *
 * @method Reponses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponses[]    findAll()
 * @method Reponses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponsesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponses::class);
    }

    public function save(Reponses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reponses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDistinctFormNumbers()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('DISTINCT r.formNumber')
            ->getQuery();

        return $qb->getResult();
    }

    public function findDistinctFormNumbersByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->select('DISTINCT r.formNumber')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function countDistinctFormNumbers()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(DISTINCT r.formNumber) as formCount')
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function countDistinctFormNumbersByUser($user)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(DISTINCT r.formNumber) as formCount')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        return $qb->getSingleScalarResult();
    }


    public function findByFormNumberCustom($formNumber)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.formNumber = :formNumber')
            ->setParameter('formNumber', $formNumber)
            ->getQuery()
            ->getResult();
    }

    public function findByFormNumberCustomAndUser($formNumber, $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.formNumber = :formNumber')
            ->andWhere('r.user = :user')
            ->setParameter('formNumber', $formNumber)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Reponses[] Returns an array of Reponses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reponses
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
