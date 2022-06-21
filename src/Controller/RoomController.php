<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RoomController extends AbstractController
{
    /**
     * @Route("/room", name="room_display")
     */

    public function displayRooomsPicture()
    {
        return $this->render('front/room/displayRoomsPicture.html.twig');
    }

    /**
     * @Route("/room/details", name="room_details")
     */
    public function room(RoomRepository $roomRepository)
    {

        return $this->render('front/room/room.html.twig', []);
    }
    /**
     * @Route("/room/barService", name="room_barService")
     */
    public function barService(RoomRepository $roomRepository)
    {

        return $this->render('front/room/barService.html.twig', []);
    }

      /**
     * @Route("/room/suites", name="room_suites")
     */
    public function suites(RoomRepository $roomRepository)
    {

        return $this->render('front/room/suites.html.twig', []);
    }
}
