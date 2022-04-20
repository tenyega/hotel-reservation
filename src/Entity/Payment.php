<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{

    public const Payment_Coupon = '2FNS151';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $TotalCharges;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PaymentType;

    /**
     * @ORM\OneToOne(targetEntity=Reservation::class, inversedBy="payment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ReservationID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CouponPromo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalCharges(): ?float
    {
        return $this->TotalCharges;
    }

    public function setTotalCharges(float $TotalCharges): self
    {
        $this->TotalCharges = $TotalCharges;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->PaymentType;
    }

    public function setPaymentType(string $PaymentType): self
    {
        $this->PaymentType = $PaymentType;

        return $this;
    }

    public function getReservationID(): ?Reservation
    {
        return $this->ReservationID;
    }

    public function setReservationID(Reservation $ReservationID): self
    {
        $this->ReservationID = $ReservationID;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCouponPromo(): ?string
    {
        return $this->CouponPromo;
    }

    public function setCouponPromo(?string $CouponPromo): self
    {
        $this->CouponPromo = $CouponPromo;

        return $this;
    }
}
