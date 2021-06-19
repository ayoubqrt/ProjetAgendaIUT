<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 * @UniqueEntity("reference")
 */
class Matiere implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToMany(targetEntity=Professeur::class, inversedBy="matieres")
     */
    private $professeurs;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="matiere")
     */
    private $cours;

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->titre, $this->reference);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'reference' => $this->reference
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|Professeur[]
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) 
        {
            $this->professeurs[] = $professeur;
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        $this->professeurs->removeElement($professeur);

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
            $cours->setMatiere($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        if ($this->cours->removeElement($cours)) {
            // set the owning side to null (unless already changed)
            if ($cours->getMatiere() === $this) {
                $cours->setMatiere(null);
            }
        }

        return $this;
    }
}
