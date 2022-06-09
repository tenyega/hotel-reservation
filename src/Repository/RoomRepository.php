<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    protected $reservationRepository;

    public function __construct(ManagerRegistry $registry, ReservationRepository $reservationRepository)
    {
        parent::__construct($registry, Room::class);
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Room $entity, bool $flush = true): void
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
    public function remove(Room $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Room[] Returns an array of Room objects
     */

    public function findByExampleField($roomno)
    {

        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo = :val')
            ->setParameter('val', $roomno)
            ->orderBy('r.RoomNo', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // this returns the rooms which is not reserved on the dates supplied by the client 
    //this method checks in the array of roomsAlreadyReserved and brings all the room details whose room no is not in the array roomsAlreadyReserved
    public function findRoomSuggestions($roomsAlreadyReserved)
    {

        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo NOT IN (:vals)')
            ->setParameters(array('vals' => $roomsAlreadyReserved))
            ->orderBy('r.RoomNo', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function checkRoomNo($roomNo)
    {

        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo = :val')
            ->setParameter('val', $roomNo)
            ->getQuery()
            ->getResult();
    }


    // Here i m getting the details of the room which is booked between the dates mentioned by the client
    public function getRoomsReserved($arrivalDate, $departureDate)
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'r1')
            ->from('App\Entity\Reservation', 'r1')
            ->andWhere(':arrivalDate  BETWEEN r1.CheckInDate  AND r1.CheckOutDate')
            ->setParameter('arrivalDate', $arrivalDate)
            ->orWhere(':departureDate  BETWEEN r1.CheckInDate  AND r1.CheckOutDate')
            ->orWhere('r1.CheckInDate BETWEEN :arrivalDate AND :departureDate ')
            ->orWhere('r1.CheckOutDate BETWEEN :arrivalDate AND :departureDate ')
            ->setParameter('departureDate', $departureDate)
            ->having('r.RoomNo = r1.RoomNo')
            ->getQuery()
            ->getResult();
    }
}
