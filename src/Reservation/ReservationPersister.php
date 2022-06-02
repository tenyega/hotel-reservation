<?php

namespace App\Reservation;

use DateTime;
use App\Entity\Reservation;
use App\Session\SessionService;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Security\Core\Security;

class ReservationPersister
{

    protected $em;
    protected $reservationRepository;
    protected $sessionService;
    protected $userRepository;
    protected $roomRepository;
    protected $security;



    public function __construct(EntityManagerInterface $em, RoomRepository $roomRepository, Security $security, ReservationRepository $reservationRepository, SessionService $sessionService, UserRepository $userRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->security = $security;
        $this->em = $em;
        $this->sessionService = $sessionService;
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->sessionService = $sessionService;
    }

    public function persistReservation($roomNo, $userID = null)
    {
        /**  @var Reservation */
        $reservationDetails = $this->sessionService->getSessionDetails();
        if ($userID) {
            $user = $this->userRepository->find($userID);
        } else {
            $user = $this->security->getUser();
        }
        $reservation = new Reservation;

        $room = $this->roomRepository->findByExampleField($roomNo);

        $reservation->setBookingDate(new DateTime('now'))
            ->setCheckInDate($reservationDetails['CheckInDate'])
            ->setCheckOutDate($reservationDetails['CheckOutDate'])
            ->setUserID($user)
            ->setNoAdult($reservationDetails['NoAdult']);
        if ($reservationDetails['NoEnfant']) {
            $reservation->setNoEnfant($reservationDetails['NoEnfant']);
        } else {
            $reservation->setNoEnfant(0);
        }

        if ($reservationDetails['CodePromo']) {
            $reservation->setCodePromo($reservationDetails['CodePromo']);
        }
        if ($reservationDetails['SpecialDemande']) {
            $reservation->setSpecialDemande($reservationDetails['SpecialDemande']);
        }
        $checkIn = "";
        $checkOut = "";
        $checkIn = new DateTime($reservation->getCheckInDate()->format('Y-m-d'));
        $checkOut = new DateTime($reservation->getCheckOutDate()->format('Y-m-d'));

        $reservation->setRoomNo($roomNo);

        $total = $this->calculTotal($checkIn, $checkOut, $room[0]);

        $reservation->setTotal($total);
        $reservation->setStatus(Reservation::STATUS_PENDING);


        $this->em->persist($reservation);
        $this->em->flush();

        return $reservation;
    }

    public function calculTotal($checkIn, $checkOut, $room)
    {


        if ($checkIn === $checkOut) {
            $noOfDays = 1;
        } else {

            $noOfDays = ($checkIn->diff($checkOut))->format("%a");
        }
        $totalNoOfDays = ($noOfDays + 1);


        $total = ($totalNoOfDays * $room->getPrice()) * 100;
        return $total;
    }
}
