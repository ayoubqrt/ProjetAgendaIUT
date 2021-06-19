<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThan(propertyPath="dateHeureDebut", message="La date de fin ne peut pas être inférieure à la date de début")
     */
    private $dateHeureFin;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="cours")
     * @Assert\NotBlank()
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class, inversedBy="cours")
     */
    private $professeur;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="cours")
     */
    private $salle;

    /**
     * @ORM\OneToMany(targetEntity=AvisCours::class, mappedBy="cours", orphanRemoval=true)
     */
    private $avisCours;

    public function __construct()
    {
        $this->dateHeureDebut = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $this->dateHeureFin = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $this->dateHeureFin->add(new DateInterval('PT1H'));
        $this->avisCours = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('Cours de %s assuré par %s, le %s de %s à %s', $this->matiere, $this->professeur,
        $this->dateHeureDebut->format('d-m-Y'), $this->dateHeureDebut->format('H:i'), 
        $this->dateHeureFin->format('H:i'));
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->isHeureDebutAvant8H())
        {
            $context->buildViolation('Un cours ne peut pas démarrer avant 8h !')
                    ->atPath('dateHeureDebut')
                    ->addViolation();
        }

        if($this->isHeureFinApres20H())
        {
            $context->buildViolation('Un cours ne peut pas finir après 20h !')
                    ->atPath('dateHeureFin')
                    ->addViolation();
        }

        if(!$this->isSurUnJour())
        {
            $context->buildViolation("Un cours ne peut pas s'étaler sur plusieurs jours !")
                    ->atPath('dateHeureFin')
                    ->addViolation();
        }

        if ($this->isProfesseurDejaOccupe()) 
        {           
            $context->buildViolation('Ce professeur assure déjà un cours au même moment !')
                    ->atPath('professeur')
                    ->addViolation();             
        }

        if($this->isSalleDejaOccupee())
        {
            $context->buildViolation('Cette salle est solicitée pour un autre cours au même moment !')
                    ->atPath('salle')
                    ->addViolation();             
        }
    }

    private function isHeureDebutAvant8H(): bool
    {
        return ($this->dateHeureDebut->format('H') < 8);
    }

    private function isHeureFinApres20H(): bool
    {
        return (($this->dateHeureFin->format('H') >= 20) && ($this->dateHeureFin->format('i') > 0));
    }

    private function isSurUnJour(): bool
    {
        return ($this->dateHeureDebut->format('Y-m-d') == $this->dateHeureFin->format('Y-m-d'));
    }

    private function isProfesseurDejaOccupe(): bool
    {
        if ($this->professeur != null) 
        {
            foreach ($this->professeur->getCours() as $autreCours) 
            {              
                if(($this->id != $autreCours->getId()) && $this->coursSeChevauchent($autreCours))
                {            
                    return true;
                }
            }
        }
        return false;
    }

    private function coursSeChevauchent(Cours $autreCours): bool
    {
        return !($this->dateHeureDebut >= $autreCours->dateHeureFin || 
                 $this->dateHeureFin <= $autreCours->dateHeureDebut);
    }

    private function isSalleDejaOccupee(): bool
    {
        if($this->salle != null)
        {
            foreach ($this->salle->getCours() as $autreCours) 
            {              
                if(($this->id != $autreCours->getId()) && $this->coursSeChevauchent($autreCours) && ($autreCours->getSalle()->getId() == $this->getSalle()->getId()))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'dateHeureDebut' => $this->dateHeureDebut,   
            'dateHeureFin' => $this->dateHeureFin,
            'type' => $this->type,
            'matiere' => $this->matiere,
            'professeur' => $this->professeur,
            'salle' => $this->salle
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;

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

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): self
    {
        $this->professeur = $professeur;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * @return Collection|AvisCours[]
     */
    public function getAvisCours(): Collection
    {
        return $this->avisCours;
    }

    public function addAvisCour(AvisCours $avisCour): self
    {
        if (!$this->avisCours->contains($avisCour)) {
            $this->avisCours[] = $avisCour;
            $avisCour->setCours($this);
        }

        return $this;
    }

    public function removeAvisCour(AvisCours $avisCour): self
    {
        if ($this->avisCours->removeElement($avisCour)) {
            // set the owning side to null (unless already changed)
            if ($avisCour->getCours() === $this) {
                $avisCour->setCours(null);
            }
        }

        return $this;
    }
}
