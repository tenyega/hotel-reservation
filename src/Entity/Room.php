<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{

    public const TYPE_AC = "Climatisation";
    public const TYPE_NONAC = "Sans Climatisation";


    public const BED_SIMPLE = "1 X LIT SIMPLE";
    public const BED_DOUBLE = "1 X LIT DOUBLE";

    public const ROOM_IMAGE1 = "Img/first.jpg";
    public const ROOM_IMAGE2 = "Img/second.jpg";
    public const ROOM_IMAGE3 = "Img/third.jpg";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $RoomNo;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $Floor;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $MaxCapacity;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $Type;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $IsSmoking;

    /**
     * @ORM\Column(type="string", length=255,nullable=false)
     */
    private $FacilityPossible;

    /**
     * @ORM\Column(type="float",nullable=false)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bedding = self::BED_SIMPLE;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255,nullable=false)
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $otherPicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $anotherpicture;



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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBedding(): ?string
    {
        return $this->bedding;
    }

    public function setBedding(string $bedding): self
    {
        $this->bedding = $bedding;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getOtherPicture(): ?string
    {
        return $this->otherPicture;
    }

    public function setOtherPicture(?string $otherPicture): self
    {
        $this->otherPicture = $otherPicture;

        return $this;
    }

    public function getAnotherpicture(): ?string
    {
        return $this->anotherpicture;
    }

    public function setAnotherpicture(?string $anotherpicture): self
    {
        $this->anotherpicture = $anotherpicture;

        return $this;
    }
}
