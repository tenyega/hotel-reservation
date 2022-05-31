<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Event\ReservationConfirmationEvent;
use App\Repository\RoomRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ReservationConfirmationSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    protected $security;
    protected $roomRepository;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security, RoomRepository $roomRepository)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
        $this->roomRepository = $roomRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            'reservation.success' => 'sendSuccessEmail'
        ];
    }
    public function sendSuccessEmail(ReservationConfirmationEvent $reservationConfirmationEvent)
    {
        $publicDirectory = getcwd();
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf';

        // // 1. get user connected - to get his email id. - as we are not in abstractcontroller thats why Security
        // /**@var User */
        // $currentUser = $this->security->getUser();
        // if (!$currentUser) {
        //     return;
        // }
        // $userEmail = $currentUser->getEmail();

        // //2. get the commande  PurchaseSuccessEvent has the purchase
        // $reservation = $reservationConfirmationEvent->getReservation();
        // //3. write a mail nouveau templateEmail
        // $email = new TemplatedEmail();
        // // $email->to(new Address($userEmail, $currentUser->getFullName()))
        $email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "information du mail"))
            ->to("tenyega23@yahoo.com")
            ->text("Vous avez une reservation avec la reservation ID " . $reservationConfirmationEvent->getReservation()->getId())
            ->htmlTemplate('front/emails/reservation_success.html.twig')
            ->context([
                'reservation' => $reservationConfirmationEvent->getReservation(),
                'room' => $this->roomRepository->findByExampleField($reservationConfirmationEvent->getReservation()->getRoomNo()),
                'user' => "yega"
            ])
            ->attachFromPath($pdfFilepath)
            ->subject("Brovo votre reservation pour  ({$reservationConfirmationEvent->getReservation()->getCheckInDate()->format('Y-m-d')}) a été bien enregistrée.");

        // 4. send a mail mailer Interface 
        $this->mailer->send($email);

        //  dd($email);
        $this->logger->notice('Email envoyé pour la commande no ' . $reservationConfirmationEvent->getReservation()->getId());
    }
}
