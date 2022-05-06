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
            ->getQuery()
            ->getResult();
    }



    public function findRoomsDispo($roomNoReserved)
    {
        $roomNoReserved = (int)$roomNoReserved;
        //dd($roomNoReserved);
        return $this->createQueryBuilder('r')
            ->andWhere('r.RoomNo != :val')
            ->setParameter('val', $roomNoReserved)
            ->orderBy('r.RoomNo', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
