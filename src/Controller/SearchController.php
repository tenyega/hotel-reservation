<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Session\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class SearchController extends AbstractController
{
    /**
     * @Route("/", name="front_search", methods={"GET","POST"})
     */

    public function search(Request $request, RoomRepository $roomRepository, ReservationRepository $reservationRepository, SessionService $sessionService): Response
    {
        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);

        $roomsAlreadyReserved = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //dd($data);

            // adding form data to the session so that i can give it to the search criteria
            $sessionService->add($data);


            $arrivalDate = $data['CheckInDate']->format('Y-m-d');
            $departureDate = $data['CheckOutDate']->format('Y-m-d');

            // this getRoom contains the details of the room which has been booked on this particular dates supplied by the client 
            $getRoom = $roomRepository->getRoomsReserved($arrivalDate, $departureDate);
            if ($getRoom != null) {
                for ($i = 0; $i < count($getRoom) - 1; $i++) {
                    $roomsAlreadyReserved[] = $getRoom[$i]->getRoomNo();
                    //roomsAlreadyReserved contains all the RoomNo of the rooms which are booked on this particular date supplied by the client 
                }

                // so finally the rooms which are available are filtered here. 
                $roomSuggestion = $roomRepository->findRoomSuggestions($roomsAlreadyReserved);
                dump($roomSuggestion);
            }


            if ($roomsAlreadyReserved === null) {
                $roomSuggestion = $roomRepository->findAll();
            }


            return $this->render('front/search/roomsAvailable.html.twig', [
                'form' => $form->createView(),
                'rooms' => $roomSuggestion
            ]);
        }

        return $this->render('front/search/search.html.twig', [
            'rooms' => $roomsAlreadyReserved,
            'form' => $form->createView(),
        ]);
    }
}
