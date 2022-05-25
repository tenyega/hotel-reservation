<?php

namespace App\Reservation;

use DateTime;
use App\Entity\Reservation;
use App\Session\SessionService;
use App\Repository\CustomerRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;


class ReservationPersister
{

    protected $em;
    protected $reservationRepository;
    protected $sessionService;
    protected $customerRepository;
    protected $paymentRepository;

    public function __construct(EntityManagerInterface $em, ReservationRepository $reservationRepository, SessionService $sessionService, CustomerRepository $customerRepository, PaymentRepository $paymentRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->em = $em;
        $this->sessionService = $sessionService;
        $this->customerRepository = $customerRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function persistReservation($roomNo)
    {
        /**  @var Reservation */
        $reservationDetails = $this->sessionService->getSessionDetails();

        $reservation = new Reservation;
        $customer = $this->customerRepository->find('336');
        $payment = $this->paymentRepository->find('279');

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

        $reservation->setRoomNo($roomNo);

        $reservation->setPayment($payment);
        $reservation->setTotal(3000);


        $this->em->persist($reservation);
        $this->em->flush();

        return $reservation;
    }
}
