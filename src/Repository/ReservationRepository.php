<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */

    public function findByExampleField($customerID)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.CustomerID = :val')
            ->setParameter('val', $customerID)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */

    // public function findByRoomNo($roomNo)
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.RoomNo = :val')
    //         ->setParameter('val', $roomNo)
    //         ->getQuery()
    //         ->getResult();
    // }



    public function findReservation($arrivalDate, $departureDate)
    {
        // dd('inside reservationReposit');
        return $this->createQueryBuilder('r')
            ->andWhere('r.CheckInDate =  :val')
            ->andWhere('r.CheckOutDate = :val2')
            ->setParameter('val', $arrivalDate)
            ->setParameter('val2', $departureDate)
            ->getQuery()
            ->getResult();
    }
}
