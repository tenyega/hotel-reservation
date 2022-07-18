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
    private $oldResa;
    public function __construct(RoomRepository $roomRepository, ReservationPersister $reservationPersister)
    {
        $this->roomRepository = $roomRepository;
        $this->reservationPersister = $reservationPersister;
    }

    /**
     * @Route("/reservation", name="reservation_show")
     */
    public function show(ReservationRepository $reservationRepository): Response
    {
        // this route is to show the reservation of the logged user
        /** @var User */
        $user = $this->getUser();
        if ($user) {
            // getting all the reservation of this logged user with status PAID and the checkin date after today's date. 
            $reservations = $reservationRepository->findByExampleField($user->getId());


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
    public function confirmation($id, ReservationRepository $reservationRepository, RoomRepository $roomRepository, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        // this route is called only after the payment is successful so the status of the reservation is changed to PAID here.
        $reservation = $reservationRepository->find($id);
        $roomNo = $reservation->getRoomNo();
        $reservation->setStatus(Reservation::STATUS_PAID);
        $em->persist($reservation);
        $em->flush();
        $room = $roomRepository->findByExampleField($roomNo);

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
        // $this->oldResa = clone $reservation;
        $this->saveOldResa($reservation);
        $oldResa = $this->getOldResa();
        $roomno = $reservation->getRoomNo();
        $room = $roomRepository->findByExampleField($roomno);

        $form = $this->createForm(ReservationType::class, $reservation);

        $total = $reservation->getTotal();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $reservation->setStatus(Reservation::STATUS_PENDING);

            $checkIn = $form->getData()->getCheckInDate();
            $checkOut = $form->getData()->getCheckOutDate();
            $myRoom = $room[0];

            $this->newTotal = $this->reservationPersister->calculTotal($checkIn, $checkOut, $myRoom);
            $this->diffTotal = $this->newTotal - $total;

            $reservation->setTotal($this->newTotal);


            $resaID = $reservation->getId();
            $em->flush();


            if ($total < $this->newTotal) {
                return $this->render('front/reservation/modificationConfirmation.html.twig', [
                    'resaID' => $resaID,
                    'reservation' => $reservation,
                    'diffTotal' => $this->diffTotal,
                    'room' => $room,
                    'oldResa' => $oldResa
                ]);
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
     * @Route("/payment/{resaID}/{diffTotal}", name="reservation_payment", priority=1, methods={"GET"})
     */


    public function payment($resaID, $diffTotal, ReservationRepository $reservationRepository, StripeService $stripeService)
    {


        $reservation = $reservationRepository->find($resaID);

        $total = $reservation->getTotal();
        if ($diffTotal) {
            $paymentIntent = $stripeService->getPaymentIntent($diffTotal);
        } else {
            $paymentIntent = $stripeService->getPaymentIntent($total);
        }

        return $this->render('front/payment/payment.html.twig', [
            'reservation' => $reservation,
            'clientSecret' => $paymentIntent->client_secret,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }

    public function saveOldResa($reservation)
    {
        $this->oldResa = clone $reservation;
    }

    public function getOldResa()
    {
        return $this->oldResa;
    }
}
