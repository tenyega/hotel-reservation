<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Form\ReservationType;
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
    public function reserver($roomNo, Request $request)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);


        $formData = $form->getData();
        // dd($formData);

        return $this->render('front/reservation/reserver.html.twig');
    }
}
