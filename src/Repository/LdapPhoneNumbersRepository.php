<?php

namespace App\Repository;

use App\Entity\LdapPhoneNumbers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LdapPhoneNumbers|null find($id, $lockMode = null, $lockVersion = null)
 * @method LdapPhoneNumbers|null findOneBy(array $criteria, array $orderBy = null)
 * @method LdapPhoneNumbers[]    findAll()
 * @method LdapPhoneNumbers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LdapPhoneNumbersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LdapPhoneNumbers::class);
    }

    // /**
    //  * @return LdapPhoneNumbers[] Returns an array of LdapPhoneNumbers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhoneNumbers
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
