<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Invoice\InvoiceGenerator;
use Symfony\Component\Mime\Email;
use App\Repository\RoomRepository;
use Symfony\Component\Mime\Address;
use App\Event\ReservationConfirmationEvent;
use App\Event\UserVerification;
use App\Event\UserVerificationEvent;
use Stripe\Invoice;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class UserEmailVerificationSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    protected $security;
    protected $roomRepository;
    protected $invoiceGenerator;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security, RoomRepository $roomRepository)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'verification.success' => 'sendVerifiedEmail'
        ];
    }
    public function sendVerifiedEmail(UserVerificationEvent $userVerificationEvent)
    {

        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@gmail.com", "Admin de site Doenkhang"))
        //     ->to("tenyega23@yahoo.com")
        //     ->text("demande d'inscription  pour " . $userVerificationEvent->getOurUser()->getEmail())
        //     ->html("<h1>Merci de clicquer sur le lien dessous pour confirmer votre email adresse </h1>

        //     <a href='{{ path('front_emailConfirmation',{'email': $userVerificationEvent->getOurUser()->getEmail() } }}'></a>
        //                         ")
        //     ->context([
        //         'user' => $userVerificationEvent->getOurUser()->getEmail()
        //     ])

        //     ->subject("Confirmation votre email");

        // // 4. send a mail mailer Interface 
        // $this->mailer->send($email);


        $email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "Admin de site Doenkhang"))
            ->to($userVerificationEvent->getOurUser()->getEmail())
            ->text("demande d'inscription  pour " . $userVerificationEvent->getOurUser()->getEmail())
            ->htmlTemplate('front/emails/verification_success.html.twig')
            ->context([
                'url' => "http://localhost/final%20project/hotel/public/confirmationEmail/" . $userVerificationEvent->getOurUser()->getEmail()
            ])

            ->subject("Confirmation de votre email");

        // 4. send a mail mailer Interface 
        $this->mailer->send($email);
    }
}
