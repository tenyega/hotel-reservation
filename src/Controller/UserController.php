<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Stripe\StripeService;
use App\Session\SessionService;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Event\UserVerificationEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\Reservation\ReservationPersister;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/userDetail/{roomNo}", name="reservation_userDetailForm", defaults={"roomNo" = null})
     */

    public function userDetailForm($roomNo, UserPasswordHasherInterface $encoder, EventDispatcherInterface $dispatcher, Security $security, UserRepository $userRepository, Request $request, ReservationPersister $reservationPersister, EntityManagerInterface $em, SessionService $sessionService)
    {

        $user = $security->getUser();
        $errorMsg = "";
        if (!$user) {
            $user = new User;
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                // dd($data->getUserIdentifier());
                $userExist = $userRepository->checkUser($data->getUserIdentifier());
                if (!$userExist) {
                    $user->setRoles(['ROLE_USER']);

                    $hashedPassword = $encoder->hashPassword($user, $data->getPassword());
                    $user->setPassword($hashedPassword);
                    $user->setIsConfirmed(false);

                    $em->persist($user);
                    $em->flush();
                    $userID = $user->getId();
                    $userVerificationEvent = new UserVerificationEvent($user);
                    $dispatcher->dispatch($userVerificationEvent, 'verification.success');

                    $diffTotal = 0;
                    if ($roomNo) {
                        $reservation = $reservationPersister->persistReservation($roomNo, $userID);
                        $resaID = $reservation->getId();

                        $sessionService->add(array($reservation));
                        // dd($sessionService->getSessionDetails());
                        return $this->redirectToRoute('security_login');
                    }


                    return $this->redirectToRoute('security_login');
                } else {
                    $errorMsg = "Email existant";
                }
            }
            return $this->render('front/reservation/userDetailForm.html.twig', [
                'form' => $form->createView(),
                'errorMsg' => $errorMsg
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
        // return $this->render('front/map/gMap.html.twig');
        return $this->render('front/map/myMap.html.twig');
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
    /**
     * @Route("/confirmationEmail/{email}", name="front_emailConfirmation")
     */

    public function emailConfirmation($email, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findUserWithEmail($email);
        if ($user) {
            $user->setIsConfirmed(true);
            $em->persist($user);
            $em->flush();
            return $this->render('front/email/emailConfirmation.html.twig');
        }
    }
}
