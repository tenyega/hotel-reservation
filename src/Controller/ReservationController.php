<?php

namespace App\Controller;

use DateTime;
use App\Entity\Room;
use App\Entity\Customer;
use App\Form\SearchType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Session\SessionService;
use Doctrine\ORM\EntityManager;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Event\ReservationConfirmationEvent;
use App\Repository\CustomerRepository;
use App\Reservation\ReservationPersister;
use App\Stripe\StripeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReservationController extends AbstractController
{

    protected $roomRepository;
    protected $reservationPersister;
    protected $isUpdate = false;
    protected $newTotal = 0;

    public function __construct(RoomRepository $roomRepository, ReservationPersister $reservationPersister)
    {
        $this->roomRepository = $roomRepository;
        $this->reservationPersister = $reservationPersister;
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
     * @Route("/reservation/confirmation/{id}", name="reservation_confirmation")
     */
    public function confirmation($id, ReservationRepository $reservationRepository, RoomRepository $roomRepository, CustomerRepository $customerRepository, SessionService $sessionService, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {

        // /**  @var Reservation */
        // $reservationDetails = $sessionService->getSessionDetails();

        // $reservation = new Reservation;
        // $customer = $customerRepository->find('336');
        // $payment = $paymentRepository->find('279');
        // /** @var Room */
        // $room = $this->roomRepository->findByExampleField($roomNo);

        // //dd($reservationDetails['CheckInDate']);
        // // dd($reservationDetails[0]['value']['arrivalDate']);
        // $reservation->setBookingDate(new DateTime('now'))
        //     ->setCheckInDate($reservationDetails['CheckInDate'])
        //     ->setCheckOutDate($reservationDetails['CheckOutDate'])
        //     ->setCustomerID($customer)
        //     ->setNoAdult($reservationDetails['NoAdult']);
        // if ($reservationDetails['NoEnfant']) {
        //     $reservation->setNoEnfant($reservationDetails['NoEnfant']);
        // } else {
        //     $reservation->setNoEnfant(0);
        // }

        // if ($reservationDetails['CodePromo']) {
        //     $reservation->setCodePromo($reservationDetails['CodePromo']);
        // }
        // if ($reservationDetails['SpecialDemande']) {
        //     $reservation->setSpecialDemande($reservationDetails['SpecialDemande']);
        // }

        // $reservation->setRoomNo($roomNo);

        // $reservation->setPayment($payment);


        $reservation = $reservationRepository->find($id);
        $roomNo = $reservation->getRoomNo();
        $reservation->setStatus(Reservation::STATUS_PAID);
        $em->persist($reservation);
        $em->flush();
        $room = $roomRepository->findByExampleField($roomNo);
        // dd($room);
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
        $this->isUpdate = true;
        $reservation = $reservationRepository->find($id);
        $roomno = $reservation->getRoomNo();
        $room = $roomRepository->findByExampleField($roomno);
        //dd($reservation);
        $form = $this->createForm(ReservationType::class, $reservation);


        $total = $reservation->getTotal();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkIn = $form->getData()->getCheckInDate();
            $checkOut = $form->getData()->getCheckOutDate();
            $myRoom = $room[0];

            $newTotal = $this->reservationPersister->calculTotal($checkIn, $checkOut, $myRoom);
            $reservation->setTotal($newTotal);
            $em->flush();
            dump($total);
            dump($newTotal);
            if ($total < $newTotal) {
                return $this->redirectToRoute('reservation_payment', [
                    'roomNo' => $roomno,
                    'reservation' => $reservation
                ]);
            }
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

    /**
     * @Route("/reservation/pay/{roomNo}", name="reservation_payment", priority=1)
     */

    public function payment($roomNo, StripeService $stripeService, Reservation $reservation=null, EntityManagerInterface $em, SessionService $sessionService, CustomerRepository $customerRepository, EventDispatcherInterface $dispatcher)
    {

        if ($this->isUpdate) {
            $paymentIntent = $stripeService->getPaymentIntent($this->newTotal, $reservation);
        } else {
            $reservation = $this->reservationPersister->persistReservation($roomNo);
        }

        $reservationEvent = new ReservationConfirmationEvent($reservation);
        $dispatcher->dispatch($reservationEvent, 'reservation.success');


        $paymentIntent = $stripeService->getPaymentIntent($reservation->getTotal(), $reservation);

        return $this->render('front/payment/payment.html.twig', [
            'reservation' => $reservation,
            'clientSecret' => $paymentIntent->client_secret,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}
