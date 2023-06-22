<?php

namespace App\Entity;

use App\Repository\TypeBienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Trait\EntityTimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('type')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TypeBienRepository::class)]
class TypeBien
{

    use EntityTimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $type;

    #[ORM\OneToMany(mappedBy: 'typeBien', targetEntity: Bien::class)]
    private Collection $biens;

    #[ORM\Column(length: 255)]
    
    private ?string $categorie;

    #[ORM\Column(nullable: true)]
    private ?bool $favori = null;


    public function __construct()
    {
        $this ->createdAt = new \DateTimeImmutable;
        $this ->updatedAt= new \DateTimeImmutable();
        $this->biens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
  
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this-> categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Bien>
     */
    public function getBiens(): Collection
    {
        return $this->biens;
    }

    public function addBien(Bien $bien): self
    {
        if (!$this->biens->contains($bien)) {
            $this->biens->add($bien);
            $bien->setTypeBien($this);
        }

        return $this;
    }

    public function removeBien(Bien $bien): self
    {
        if ($this->biens->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getTypeBien() === $this) {
                $bien->setTypeBien(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        if ($this->getCategorie() == 'a_louer') {
            return $this->getType(). ' - A louer';
        } else {
            return $this->getType(). ' - A vendre';
        }
    }

    public function isFavori(): ?bool
    {
        return $this->favori;
    }

    public function setFavori(?bool $favori): self
    {
        $this->favori = $favori;

        return $this;
    }


    

}
