<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    /**
     * @Route("/", name="front_search", methods={"GET","POST"})
     */

    public function search(Request $request, RoomRepository $roomRepository, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $rooms = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $arrivalDate = $data['arrivalDate']->format('Y-m-d');
            $departureDate = $data['departureDate']->format('Y-m-d');

            $roomNo = 0;
            $roomNo = $reservationRepository->findReservation($arrivalDate, $departureDate);
            // dd($roomNo);
            $rooms = [];
            if ($roomNo != null) {
                $roomNoReserved = $roomNo[0]->getRoomNo();

                $rooms = $roomRepository->findRoomsDispo($roomNoReserved);
                if ($rooms === []) {
                    return $this->render('front/search/noresult.html.twig');
                }
            } else {
                $rooms = $roomRepository->findBy(['Type' => 'Climatisation'], ['Type' => 'ASC'], 3);
            }

            return $this->render('front/search/roomsAvailable.html.twig', [
                'form' => $form->createView(),
                'rooms' => $rooms
            ]);
        }

        return $this->render('front/search/search.html.twig', [
            'rooms' => $rooms,
            'form' => $form->createView(),
        ]);
    }
}
