<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $BookingDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CheckInDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CheckOutDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumberOfBeds;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SpecialDemande;

    /**
     * @ORM\Column(type="integer")
     */
    private $RoomNo;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, mappedBy="ReservationID", cascade={"persist", "remove"})
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CustomerID;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->BookingDate;
    }

    public function setBookingDate(\DateTimeInterface $BookingDate): self
    {
        $this->BookingDate = $BookingDate;

        return $this;
    }

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->CheckInDate;
    }

    public function setCheckInDate(\DateTimeInterface $CheckInDate): self
    {
        $this->CheckInDate = $CheckInDate;

        return $this;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->CheckOutDate;
    }

    public function setCheckOutDate(\DateTimeInterface $CheckOutDate): self
    {
        $this->CheckOutDate = $CheckOutDate;

        return $this;
    }

    public function getNumberOfBeds(): ?int
    {
        return $this->NumberOfBeds;
    }

    public function setNumberOfBeds(int $NumberOfBeds): self
    {
        $this->NumberOfBeds = $NumberOfBeds;

        return $this;
    }

    public function getSpecialDemande(): ?string
    {
        return $this->SpecialDemande;
    }

    public function setSpecialDemande(string $SpecialDemande): self
    {
        $this->SpecialDemande = $SpecialDemande;

        return $this;
    }

    public function getRoomNo(): ?int
    {
        return $this->RoomNo;
    }

    public function setRoomNo(int $RoomNo): self
    {
        $this->RoomNo = $RoomNo;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        // set the owning side of the relation if necessary
        if ($payment->getReservationID() !== $this) {
            $payment->setReservationID($this);
        }

        $this->payment = $payment;

        return $this;
    }

    public function getCustomerID(): ?Customer
    {
        return $this->CustomerID;
    }

    public function setCustomerID(?Customer $CustomerID): self
    {
        $this->CustomerID = $CustomerID;

        return $this;
    }
}
