<?php

namespace App\Reservation;

use DateTime;
use App\Entity\Reservation;
use App\Session\SessionService;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;

class ReservationPersister
{

    protected $em;
    protected $reservationRepository;
    protected $sessionService;
    protected $customerRepository;
    protected $roomRepository;


    public function __construct(EntityManagerInterface $em, RoomRepository $roomRepository, ReservationRepository $reservationRepository, SessionService $sessionService, CustomerRepository $customerRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->em = $em;
        $this->sessionService = $sessionService;
        $this->customerRepository = $customerRepository;
        $this->roomRepository = $roomRepository;
    }

    public function persistReservation($roomNo)
    {
        /**  @var Reservation */
        $reservationDetails = $this->sessionService->getSessionDetails();

        $reservation = new Reservation;
        $customer = $this->customerRepository->find('854');

        $room = $this->roomRepository->findByExampleField($roomNo);
        dump($room);
        $reservation->setBookingDate(new DateTime('now'))
            ->setCheckInDate($reservationDetails['CheckInDate'])
            ->setCheckOutDate($reservationDetails['CheckOutDate'])
            ->setCustomerID($customer)
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
