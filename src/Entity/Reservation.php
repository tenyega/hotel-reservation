<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    public const CODE_PROMO = 'Dolma123';
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
     * @ORM\Column(type="date", length=255)
     */
    private $CheckInDate;

    /**
     * @ORM\Column(type="date", length=255)
     */
    private $CheckOutDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $NoAdult;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SpecialDemande;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NoEnfant;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, mappedBy="ReservationID", cascade={"persist", "remove"})
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CustomerID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CodePromo;

    /**
     * @ORM\Column(type="integer")
     */
    private $RoomNo;



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

    public function getCheckInDate(): ?\DateTime
    {
        return $this->CheckInDate;
    }

    public function setCheckInDate(\DateTime $CheckInDate): self
    {
        $this->CheckInDate = $CheckInDate;

        return $this;
    }

    public function getCheckOutDate(): ?\DateTime
    {
        return $this->CheckOutDate;
    }

    public function setCheckOutDate(\DateTime $CheckOutDate): self
    {
        $this->CheckOutDate = $CheckOutDate;

        return $this;
    }

    public function getNoAdult(): ?int
    {
        return $this->NoAdult;
    }

    public function setNoAdult(int $NoAdult): self
    {
        $this->NoAdult = $NoAdult;

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

    public function getNoEnfant(): ?int
    {
        return $this->NoEnfant;
    }

    public function setNoEnfant(int $NoEnfant): self
    {
        $this->NoEnfant = $NoEnfant;

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

    public function getCodePromo(): ?string
    {
        return $this->CodePromo;
    }

    public function setCodePromo(?string $CodePromo): self
    {
        $this->CodePromo = $CodePromo;

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
}
