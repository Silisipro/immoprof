<?php

namespace App\Utils\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait EntityTimestampTrait
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateAjout = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModif = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $deleted = false;

    public function __construct()
    {
        $this->dateAjout = new \DateTimeImmutable();
        $this->dateModif = new \DateTime();
        $this->deleted = false;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDateAjout(): ?\DateTimeImmutable
    {
        return $this->dateAjout;
    }

    /**
     * @param \DateTimeImmutable|null $dateAjout
     */
    public function setDateAjout(?\DateTimeImmutable $dateAjout): void
    {
        $this->dateAjout = $dateAjout;
    }

    /**
     * @return bool|null
     */
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool|null $deleted
     */
    public function setDeleted(?bool $deleted): void
    {
        $this->deleted = $deleted;
    }

}

