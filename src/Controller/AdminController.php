<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Room;
use Sentry\SentrySdk;
use App\Form\RoomType;
use App\Entity\Comment;
use App\Form\CommentFormType;
use PhpParser\Node\Stmt\Label;
use App\Repository\RoomRepository;

use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin/chart/{roomNo}", name="admin_chart_index")
     * @IsGranted("ROLE_ADMIN", message="Vous devez etre administrateur du site pour pouvoir acceder !!!")
     */
    // this route is for the display of the charts for the room occupency rate per room 
    public function index($roomNo, ReservationRepository $reservationRepository)
    {
        // this method  is to find the reservations of a particular room which are of the status PAID.
        $reservations = $reservationRepository->findAllByRoomNo($roomNo);

        function daysCalcul($checkIn, $checkOut)
        {
            if ($checkIn == $checkOut) {
                return 1;
            } else {
                return (date_diff($checkIn, $checkOut)->d + 1); // here 1 is to take into account both the checkin and check out date, taking into account the days of the date by using ->d
            }
        }
        $dataPoints = [];

        $janDiff = $febDiff = $marDiff = $aprilDiff = $mayDiff = $juneDiff = $julyDiff = $augDiff = $septDiff = $octDiff = $novDiff = $decDiff = 0;

        foreach ($reservations as $key => $reservation) {
            $resaMonth = date("m", strtotime($reservation->getCheckInDate()->format('Y-m-d'))); // to get the reservation month in integer
            $checkIn = $reservation->getCheckInDate();
            $checkOut = $reservation->getCheckOutDate();


            switch ($resaMonth) {
                case 1:
                    $janDiff = $janDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 2:
                    $febDiff = $febDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 3:
                    $marDiff = $marDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 4:
                    $aprilDiff = $aprilDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 5:
                    $mayDiff = $mayDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 6:
                    $juneDiff = $juneDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 7:
                    $julyDiff = $julyDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 8:
                    $augDiff = $augDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 9:
                    $septDiff = $septDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 10:
                    $octDiff = $octDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 11:
                    $novDiff = $novDiff + daysCalcul($checkIn, $checkOut);
                    break;

                case 12:
                    $decDiff = $decDiff + daysCalcul($checkIn, $checkOut);
                    break;
            }
        }
        // adding the arrays to an array.  so our dataPoints will be an array of an array. 2D array
        $dataPoints[] = array('label' => 'Janvier', 'y' => $janDiff);
        $dataPoints[] = array('label' => 'fevrier', 'y' => $febDiff);
        $dataPoints[] = array('label' => 'Mars', 'y' => $marDiff);
        $dataPoints[] = array('label' => 'Avril', 'y' => $aprilDiff);
        $dataPoints[] = array('label' => 'Mai', 'y' => $mayDiff);
        $dataPoints[] = array('label' => 'Juin', 'y' => $juneDiff);
        $dataPoints[] = array('label' => 'Juillet', 'y' => $julyDiff);
        $dataPoints[] = array('label' => 'Aout', 'y' => $augDiff);
        $dataPoints[] = array('label' => 'Septembre', 'y' => $septDiff);
        $dataPoints[] = array('label' => 'Octobre', 'y' => $octDiff);
        $dataPoints[] = array('label' => 'Novembre', 'y' => $novDiff);
        $dataPoints[] = array('label' => 'Decembre', 'y' => $decDiff);


        return $this->render('back/chart/index.html.twig', [
            'roomNo' => $roomNo,
            'data' => json_encode($dataPoints) // encoding it to an object
        ]);
    }


    /**
     * @Route("/admin/calender/{roomNo}", name="admin_calender")
     * @IsGranted("ROLE_ADMIN", message="Vous devez etre administrateur du site pour pouvoir acceder !!!")
     */
    public function fullCalender($roomNo, ReservationRepository $reservationRepository, UserRepository $userRepository)
    {
        $events = [];
        // brings all the reservation with this room no. 
        $reservations = $reservationRepository->findByRoomNo($roomNo);
        foreach ($reservations as $r) {
            // coz i need the Last name of the person who has done the reservation
            $user = $userRepository->find($r->getUserID());
            // P1D is done to add one day so that i can add the checkout Date also (inclusive CheckoutDate)
            $interval = new DateInterval('P1D');

            if ($r->getCheckInDate()->format('Y-m-d') == $r->getCheckOutDate()->format('Y-m-d')) {
                $events[] = array('title' => $user->getLastName(), 'start' => $r->getCheckInDate()->format('Y-m-d'), 'end' => $r->getCheckOutDate()->format('Y-m-d'));
            } else {
                $events[] = array('title' => $user->getLastName(), 'start' => ($r->getCheckInDate())->format('Y-m-d'), 'end' => ($r->getCheckOutDate()->add($interval))->format('Y-m-d'));
            }
        }


        // events: [
        //     {
        //         title: 'All Day Event',
        //         start: '2022-04-01'
        //     },
        //     {
        //         title: 'Long Event',
        //         start: '2022-04-07',
        //         end: '2022-04-10'
        //     }]
        return $this->render('back/calender/fullCalender.html.twig', [
            'events' => json_encode($events)
        ]);
    }


    /**
     * @Route("/admin/room", name="admin_room_showAll", methods={"GET"})
     */
    public function showAll(RoomRepository $roomRepository): Response
    {
        // this route is to show all the rooms available
        return $this->render('back/room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/room/new", name="admin_newRoom", methods={"GET", "POST"})
     */
    public function new(Request $request, RoomRepository $roomRepository): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // here the room no entered by the admin is checked to verify if its a unique room no 
            $roomNoExist = $roomRepository->checkRoomNo($data->getRoomNo());

            if (!$roomNoExist) {

                $roomRepository->add($room, true);

                return $this->redirectToRoute('admin_room_showAll', [], Response::HTTP_SEE_OTHER);
            } else {
                $data = $form->getData();
                $form = $this->createForm(RoomType::class, $data);
                return $this->renderForm('back/room/new.html.twig', [
                    'room' => $room,
                    'form' => $form,
                    'error' => 'Choissiez un autre numero de la chambre '
                ]);
            }
        }

        return $this->renderForm('back/room/new.html.twig', [
            'room' => $room,
            'form' => $form,
            'error' => ''
        ]);
    }

    /**
     * @Route("/admin/room/{id}", name="admin_showRoom", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        // this route is to show the details of one particular room no. 
        return $this->render('back/room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/admin/room/edit/{id}", name="admin_editRoom", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id, RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->find($id);
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomRepository->add($room, true);

            return $this->redirectToRoute('admin_room_showAll', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/room/delete/{id}", name="admin_deleteRoom", methods={"GET"})
     */
    public function delete($id, RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->find($id);

        $roomRepository->remove($room, true);


        return $this->redirectToRoute('admin_room_showAll', [], Response::HTTP_SEE_OTHER);
    }
}
