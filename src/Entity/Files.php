<?php

namespace App\Entity;

use App\Entity\Trait\EntityTimestampTrait;
use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Files
{
    use EntityTimestampTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private string $filename;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private string $type;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private string $location;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER, length: 11)]
    private int $size = 0;
    
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private string $referenceCode;


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
  
    public function getFilename(): ?string
    {
        return $this->filename;
    }
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    public function getLocation(): ?string
    {
        return $this->location;
    }
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }
    public function getSize(): ?int
    {
        return $this->size;
    }
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }
    public function getReferenceCode(): ?string
    {
        return $this->referenceCode;
    }
    public function setReferenceCode(string $referenceCode): self
    {
        $this->referenceCode = $referenceCode;
        return $this;
    }



}
