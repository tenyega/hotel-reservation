<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use App\Reservation\ReservationPersister;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/reservation/{roomNo}", name="reservation_userDetailForm")
     */

    public function userDetailForm($roomNo, Security $security, Request $request, ReservationPersister $reservationPersister, EntityManagerInterface $em, StripeService $stripeService)
    {
        $user = $security->getUser();
        if (!$user) {
            $user = new User;
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($user);
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
            return $this->render('front/reservation/userDetailForm.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            $diffTotal = 0;
            $reservation = $reservationPersister->persistReservation($roomNo);
            $resaID = $reservation->getId();
            return $this->redirectToRoute('reservation_payment', [
                'resaID' => $resaID,
                'diffTotal' => $diffTotal
            ]);
        }
    }

    /**
     * @Route("/map", name="front_map")
     */
    public function gMap()
    {
        return $this->render('front/map/gMap.html.twig');
    }

    /**
     * @Route("/feedback", name="front_feedback")
     */

    public function userFeedback(EntityManagerInterface $em, Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new DateTime('now'));
            $em->persist($comment);
            $em->flush();
            return $this->render('front/feedback/feedbackSuccess.html.twig');
        }

        return $this->render('front/feedback/userFeedback.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
