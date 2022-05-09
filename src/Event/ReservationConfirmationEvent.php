<?php

namespace App\Event;

use App\Entity\Reservation;
use Symfony\Contracts\EventDispatcher\Event;

class ReservationConfirmationEvent  extends Event
{
    private $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function getReservation(): Reservation
    {
        return $this->reservation;
    }
}
