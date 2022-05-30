<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Reservation\ReservationPersister;
use App\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * @Route("/reservation/{roomNo}", name="reservation_customerDetailForm")
     */

    public function customerDetailForm($roomNo, Request $request, ReservationPersister $reservationPersister, EntityManagerInterface $em, StripeService $stripeService)
    {
        $customer = new Customer;
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($customer);
            $em->flush();
            $data = $form->getData();
            dump($data);
            dump('form submitted');
            $diffTotal = 0;
            $reservation = $reservationPersister->persistReservation($roomNo);
            $resaID = $reservation->getId();
            return $this->redirectToRoute('reservation_payment', [
                'resaID' => $resaID,
                'diffTotal' => $diffTotal
            ]);
        }

        return $this->render('front/reservation/customerDetailForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/map", name="front_map")
     */
    public function gMap()
    {  return $this->render('front/map/gMap.html.twig');
    }
}
