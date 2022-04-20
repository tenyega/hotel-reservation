<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{

    public const TYPE_AC = "AC";
    public const TYPE_NONAC = "NON-AC";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $RoomNo;

    /**
     * @ORM\Column(type="integer")
     */
    private $Floor;

    /**
     * @ORM\Column(type="integer")
     */
    private $MaxCapacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsSmoking;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FacilityPossible;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFloor(): ?int
    {
        return $this->Floor;
    }

    public function setFloor(int $Floor): self
    {
        $this->Floor = $Floor;

        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->MaxCapacity;
    }

    public function setMaxCapacity(int $MaxCapacity): self
    {
        $this->MaxCapacity = $MaxCapacity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getIsSmoking(): ?bool
    {
        return $this->IsSmoking;
    }

    public function setIsSmoking(bool $IsSmoking): self
    {
        $this->IsSmoking = $IsSmoking;

        return $this;
    }

    public function getFacilityPossible(): ?string
    {
        return $this->FacilityPossible;
    }

    public function setFacilityPossible(string $FacilityPossible): self
    {
        $this->FacilityPossible = $FacilityPossible;

        return $this;
    }
}
