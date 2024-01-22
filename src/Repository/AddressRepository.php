<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findUserAddresses(User $user)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Address $address): void 
    {
        $this->getEntityManager()->persist($address);
        $this->getEntityManager()->flush();
    }

    public function delete(Address $address): void
    {
         $this->getEntityManager()->remove($address);
         $this->getEntityManager()->flush();
  
    }
    
    public function getShippingAddress(User $user)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->andWhere('a.id = :id')
            ->setParameter('user', $user)
            ->setParameter('id', $user->getShippingAddress())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
