<?php

namespace App\Controller;

use DateTime;
use App\Entity\Room;
use App\Entity\User;
use App\Form\SearchType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Stripe\StripeService;
use App\Session\SessionService;
use Doctrine\ORM\EntityManager;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Reservation\ReservationPersister;
use App\Event\ReservationConfirmationEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReservationController extends AbstractController
{

    protected $roomRepository;
    protected $reservationPersister;
    protected $newTotal = 0;
    public $diffTotal = 0;
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
        /** @var User */
        $user = $this->getUser();
        if ($user) {
            $reservations = $reservationRepository->findByExampleField($user->getId());
            //  dd($reservation);
            foreach ($reservations as $r) {
                $rooms = $this->roomRepository->findByExampleField($r->getRoomNo());
            }
            if ($reservations) {
                return $this->render('front/reservation/show.html.twig', [
                    'reservations' => $reservations,
                    'rooms' => $rooms
                ]);
            } else {
                return $this->render('front/reservation/noReservation.html.twig');
            }
        }
    }



    /**
     * @Route("/reservation/confirmation/{id}", name="reservation_confirmation")
     */
    public function confirmation($id, ReservationRepository $reservationRepository, RoomRepository $roomRepository, UserRepository $userRepository, SessionService $sessionService, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {

        // /**  @var Reservation */
        // $reservationDetails = $sessionService->getSessionDetails();

        // $reservation = new Reservation;
        // $user = $userRepository->find('336');
        // $payment = $paymentRepository->find('279');
        // /** @var Room */
        // $room = $this->roomRepository->findByExampleField($roomNo);

        // //dd($reservationDetails['CheckInDate']);
        // // dd($reservationDetails[0]['value']['arrivalDate']);
        // $reservation->setBookingDate(new DateTime('now'))
        //     ->setCheckInDate($reservationDetails['CheckInDate'])
        //     ->setCheckOutDate($reservationDetails['CheckOutDate'])
        //     ->setUserID($user)
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

        $reservationEvent = new ReservationConfirmationEvent($reservation);
        $dispatcher->dispatch($reservationEvent, 'reservation.success');
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
        $oldResa = clone $reservation;
        $roomno = $reservation->getRoomNo();
        $room = $roomRepository->findByExampleField($roomno);
        dump($oldResa);
        $form = $this->createForm(ReservationType::class, $reservation);

        $total = $reservation->getTotal();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($reservation);
            $reservation->setStatus(Reservation::STATUS_PENDING);

            $checkIn = $form->getData()->getCheckInDate();
            $checkOut = $form->getData()->getCheckOutDate();
            $myRoom = $room[0];
            // dd($reservation);
            $this->newTotal = $this->reservationPersister->calculTotal($checkIn, $checkOut, $myRoom);
            $this->diffTotal = $this->newTotal - $total;
            dump($this->diffTotal);
            $reservation->setTotal($this->newTotal);

            // dd($this->newTotal);
            $resaID = $reservation->getId();
            $em->flush();

            dump($total);
            dump($this->newTotal);
            if ($total < $this->newTotal) {
                return $this->render('front/reservation/modificationConfirmation.html.twig', [
                    'resaID' => $resaID,
                    'reservation' => $reservation,
                    'diffTotal' => $this->diffTotal,
                    'room' => $room,
                    'oldResa' => $oldResa
                ]);
                // return $this->redirectToRoute('reservation_payment', [
                //     'resaID' => $resaID,
                //     'reservation' => $reservation,
                //     'diffTotal' => $this->diffTotal
                // ]);
            } else {
                return $this->render('front/reservation/demandeRemboursement.html.twig');
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
     * @Route("/reservation/pay/{resaID}/{diffTotal}", name="reservation_payment", priority=1)
     */

    public function payment($resaID, $diffTotal, ReservationRepository $reservationRepository, StripeService $stripeService, EntityManagerInterface $em, SessionService $sessionService, UserRepository $userRepository)
    {

        // dump($diffTotal);
        // dd($sessionService->getSessionDetails());

        $reservation = $reservationRepository->find($resaID);
        $resaTotal = $reservation->getTotal();
        dump($resaTotal);
        dump($this->newTotal);
        $total = $reservation->getTotal();
        if ($diffTotal) {
            $paymentIntent = $stripeService->getPaymentIntent($diffTotal, $reservation);
            dump($this->newTotal);
        } else {
            $paymentIntent = $stripeService->getPaymentIntent($total, $reservation);
            dump($total);
        }



        // $paymentIntent = $stripeService->getPaymentIntent($reservation->getTotal(), $reservation);

        return $this->render('front/payment/payment.html.twig', [
            'reservation' => $reservation,
            'clientSecret' => $paymentIntent->client_secret,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}
