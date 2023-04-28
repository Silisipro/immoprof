<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Validator\Constraints as Assert;


class BienRecherche {

    #[Assert\LessThan(10000001)]
    #[Assert\Positive()]
    private ?int $maxPrice = null;

    #[Assert\Positive()]
    #[Assert\LessThan(40000)]
    private ?int $minSurface = null;







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














}







