<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ProfesseurRepository::class)
 * @UniqueEntity("email")
 */
class Professeur implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="professeur")
     */
    private $avis;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, mappedBy="professeurs")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="professeur")
     */
    private $cours;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->prenom, $this->nom);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,  
            'matieres' => $this->matieres->toArray()          
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvis(Avis $avis): self
    {
        if (!$this->avis->contains($avis)) 
        {
            $this->avis[] = $avis;
            $avis->setProfesseur($this);
        }

        return $this;
    }

    public function removeAvis(Avis $avis): self
    {
        if ($this->avis->removeElement($avis)) 
        {
            // set the owning side to null (unless already changed)
            if ($avis->getProfesseur() === $this) 
            {
                $avis->setProfesseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere))
        {
            $this->matieres[] = $matiere;
            $matiere->addProfesseur($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) 
        {
            $matiere->removeProfesseur($this);
        }

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
            $cours->setProfesseur($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        if ($this->cours->removeElement($cours)) {
            // set the owning side to null (unless already changed)
            if ($cours->getProfesseur() === $this) {
                $cours->setProfesseur(null);
            }
        }

        return $this;
    }
}
