<?php

namespace App\Controller;

use DateTime;
use App\Entity\Room;
use App\Entity\Customer;
use App\Entity\Payment;
use App\Form\SearchType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\CustomerRepository;
use App\Repository\PaymentRepository;
use App\Session\SessionService;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation_reservation")
     */
    public function reservation(Request $request): Response
    {
        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            return $this->redirectToRoute('reservation/show.html.twig', [
                'data' => $form->getData()
            ]);
        }
        return $this->render('reservation/reservation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reservation/{roomNo}", name="reservation_reserver")
     */
    public function reserver(int $roomNo, RoomRepository $roomRepository, SessionService $sessionService, EntityManagerInterface $em, CustomerRepository $customerRepository, PaymentRepository $paymentRepository)
    {

        $room = [];
        $reservationDetails = $sessionService->getSessionDetails();

        $reservation = new Reservation;
        $customer = $customerRepository->find('294');
        $payment = $paymentRepository->find('237');
        // $room = $roomRepository->find($roomNo);
        // dd($room);
        //dd($reservationDetails);
        // dd($reservationDetails[0]['value']['arrivalDate']);
        $reservation->setBookingDate(new DateTime('now'))
            ->setCheckInDate($reservationDetails[0]['value']['arrivalDate']->format('Y-m-d'))
            ->setCheckOutDate($reservationDetails[0]['value']['departureDate']->format('Y-m-d'))
            ->setCustomerID($customer)
            ->setNumberOfBeds($reservationDetails[0]['value']['rooms'])
            ->setRoomNo($roomNo)
            ->setSpecialDemande("nothing")
            ->setPayment($payment);

        $em->persist($reservation);
        $em->flush();

        return $this->render('front/reservation/reserver.html.twig');
    }
}
