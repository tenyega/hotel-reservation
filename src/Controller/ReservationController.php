<?php

namespace App\Controller;

use DateTime;
use App\Entity\Room;
use App\Entity\Payment;
use App\Entity\Customer;
use App\Form\SearchType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Session\SessionService;
use Doctrine\ORM\EntityManager;
use App\Repository\RoomRepository;
use App\Repository\PaymentRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Event\ReservationConfirmationEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{

    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }
    /**
     * @Route("/reservation", name="reservation_show")
     */
    public function show(ReservationRepository $reservationRepository, Request $request): Response
    {
        /** @var Customer */
        $user = $this->getUser();
        $reservations = $reservationRepository->findByExampleField('294');
        //  dd($reservation);


        return $this->render('front/reservation/show.html.twig', [
            'reservations' => $reservations
        ]);
    }

    /**
     * @Route("/reservation/{roomNo}", name="reservation_confirmation")
     */
    public function confirmation($roomNo, SessionService $sessionService, EntityManagerInterface $em, CustomerRepository $customerRepository, PaymentRepository $paymentRepository, EventDispatcherInterface $dispatcher)
    {

        /**  @var Reservation */
        $reservationDetails = $sessionService->getSessionDetails();

        $reservation = new Reservation;
        $customer = $customerRepository->find('322');
        $payment = $paymentRepository->find('274');
        /** @var Room */
        $room = $this->roomRepository->findByExampleField($roomNo);

        //dd($reservationDetails['CheckInDate']);
        // dd($reservationDetails[0]['value']['arrivalDate']);
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

        $em->persist($reservation);
        $em->flush();

        $reservationEvent = new ReservationConfirmationEvent($reservation);
        $dispatcher->dispatch($reservationEvent, 'reservation.success');

        // dd($reservationEvent);
        return $this->render('front/reservation/confirmation.html.twig', [
            'reservation' => $reservation,
            'room' => $room
        ]);
    }

    /**
     * @Route("/reservation/update/{id}", name="reservation_update")
     */

    public function update($id, Request $request, RoomRepository $roomRepository, ReservationRepository $reservationRepository, EntityManagerInterface $em)
    {

        $reservation = $reservationRepository->find($id);
        $roomno = $reservation->getRoomNo();
        $room = $roomRepository->findByExampleField($roomno);
        //dd($reservation);
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->render('front/reservation/confirmation.html.twig', [
                'reservation' => $reservation,
                'room' => $room

            ]);
        }
        return $this->render('front/reservation/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/reservation/cancel/{id}", name="reservation_cancel")
     */

    public function cancel($id, ReservationRepository $reservationRepository, EntityManagerInterface $em)
    {

        $reservation = $reservationRepository->find($id);
        $em->remove($reservation);
        $em->flush();



        return $this->render('front/reservation/cancel.html.twig', [
            'reservation' => $reservation
        ]);
    }
}
