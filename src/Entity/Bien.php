<?php

namespace App\Entity;


use App\Entity\Trait\EntityTimestampTrait;
use App\Repository\BienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BienRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Bien
{  

    use EntityTimestampTrait;

    public const HEAT = [
        '0'=> 'Compteur personnel',
        '1'=> 'Compteur à carte',
        '2'=> 'Decompteur',
        '3'=> 'Panneaux solaire',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\LessThan(10000001)]
    private ?float $price;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column( nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(4000)]
    private ?int $surface;

    #[ORM\Column ( nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(50)]
    private ?int $rooms = null;

    #[ORM\Column( nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(50)]
    private ?int $bedrooms = null;

    #[ORM\Column( nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(10)]
    private ?int $floor = null;

    #[ORM\Column( nullable: true)]
    private ?int $heat = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column]
    private ?bool $sold = false;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CodeFichier = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime $datepublication;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime $datelocationvente;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

   

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeBien $typeBien = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Standing $standing = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paiementLouer = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable;
        $this->updatedAt = new \DateTimeImmutable();
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getHeatType(): string
    {
        return self::HEAT[$this->heat];
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function isSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    public function getCodeFichier(): ?string
    {
        return $this->CodeFichier;
    }
    public function setCodeFichier(?string $CodeFichier): self
    {
        $this->CodeFichier = $CodeFichier;
        return $this;
    }

    public function getDatePublication(): \DateTime
    {
        return $this->datepublication;
    }

    public function setDatePublication(\DateTime $datepublication): self
    {
        $this->datepublication = $datepublication;

        return $this;
    }

    public function getDateLocationVente(): \DateTime
    {
        return $this->datepublication;
    }

    public function setDateLocationVente(\DateTime $datelocationvente): self
    {
        $this->datelocationvente = $datelocationvente;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    

    public function getTypeBien(): ?TypeBien
    {
        return $this->typeBien;
    }

    public function setTypeBien(?TypeBien $typeBien): self
    {
        $this->typeBien = $typeBien;

        return $this;
    }

    public function getStanding(): ?Standing
    {
        return $this->standing;
    }

    public function setStanding(?Standing $standing): self
    {
        $this->standing = $standing;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPaiementLouer(): ?string
    {
        return $this->paiementLouer;
    }

    public function setPaiementLouer(?string $paiementLouer): self
    {
        $this->paiementLouer = $paiementLouer;

        return $this;
    }

}
