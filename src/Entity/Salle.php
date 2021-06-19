<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 * @UniqueEntity("numero")
 */
class Salle implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", unique=true)
     * @Assert\GreaterThanOrEqual(0, message="Le numéro de salle ne peut pas être négatif.")
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="salle")
     */
    private $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s', $this->numero);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero       
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCours(Cours $cours): self
    {
        if (!$this->cours->contains($cours)) {
            $this->cours[] = $cours;
            $cours->setSalle($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        if ($this->cours->removeElement($cours)) {
            // set the owning side to null (unless already changed)
            if ($cours->getSalle() === $this) {
                $cours->setSalle(null);
            }
        }

        return $this;
    }
}
