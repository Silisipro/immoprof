<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class BienRecherche {

    #[Assert\LessThan(10000001)]
    #[Assert\Positive()]
    private ?int $maxPrice = null;

    #[Assert\Positive()]
    #[Assert\LessThan(40000)]
    private ?int $minSurface = null;

    private ?string $lieu = null;

    private ?TypeBien $typeBien = null;
    private ?Standing $standing = null;



    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice( int $maxPrice ) : BienRecherche
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }


    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    public function setMinSurface( int $minSurface ) : BienRecherche
    {
        $this->minSurface = $minSurface;

        return $this;
     }

     public function getLieu(): ?string
     {
         return $this->lieu;
     }
 
     public function setLieu( string $lieu ) : BienRecherche
     {
         $this->lieu = $lieu;
 
         return $this;
      }
    /* public function getTypebien(): Collection
    {
        return $this->typeBien;
    }

    public function setTypebien( Collection $typeBien ) : void
    {
        $this->typeBien = $typeBien;

     }*/

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

 }

