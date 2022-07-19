<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

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

    public function findByExampleField($userID)
    {
        $currentDate = new DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.UserID = :val')
            ->setParameter('val', $userID)
            ->andWhere('r.status= :val2')
            ->setParameter('val2', Reservation::STATUS_PAID)
            ->andWhere('r.CheckInDate >= :val3')
            ->setParameter('val3', $currentDate->format('Y-m-d'))
            ->orderBy('r.CheckInDate')
            ->getQuery()

            ->getResult();
    }
    /**
     * @return Reservation[] Returns an array of Reservation objects
     */

    public function findByRoomNo($roomNo)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo = :val')
            ->setParameter('val', $roomNo)
            ->andWhere('r.status= :val2')
            ->setParameter('val2', Reservation::STATUS_PAID)
            ->getQuery()
            ->getResult();
    }



    // public function findReservation($arrivalDate, $departureDate)
    // {
    //     //not using this method
    //     return $this->createQueryBuilder('r')
    //         ->andWhere(':val NOT BETWEEN r.CheckInDate  AND r.CheckOutDate')
    //         ->andWhere(':val2 NOT BETWEEN r.CheckInDate  AND r.CheckOutDate')
    //         ->setParameter('val', $arrivalDate)
    //         ->setParameter('val2', $departureDate)
    //         ->setMaxResults(3)
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findAllByRoomNo($roomNo)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo = :val')
            ->setParameter('val', $roomNo)
            ->andWhere('r.status= :val2')
            ->setParameter('val2', Reservation::STATUS_PAID)
            ->getQuery()
            ->getResult();
    }
}
