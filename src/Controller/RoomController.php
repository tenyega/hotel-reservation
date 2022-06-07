<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
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
}
