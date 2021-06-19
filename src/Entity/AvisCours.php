<?php

namespace App\Entity;

use App\Repository\AvisCoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=AvisCoursRepository::class)
 * @UniqueEntity(
 *     fields={"cours", "emailEtudiant"},
 *     errorPath="emailEtudiant",
 *     message="Cet etudiant a déjà noté ce cours."
 * )
 */
class AvisCours implements JsonSerializable
{
    use Updatable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=5)
     * @Assert\NotBlank()
     */
    private $note;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $emailEtudiant;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class, inversedBy="avisCours")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $cours;

    public function __construct(array $data = [])
    {
        $this->note = $data['note'] ?? null;
        $this->commentaire = $data['commentaire'] ?? null;
        $this->emailEtudiant = $data['emailEtudiant'] ?? null;
        $this->cours = $data['cours'] ?? null;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'commentaire' => $this->commentaire,
            'emailEtudiant' => $this->emailEtudiant
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getEmailEtudiant(): ?string
    {
        return $this->emailEtudiant;
    }

    public function setEmailEtudiant(string $emailEtudiant): self
    {
        $this->emailEtudiant = $emailEtudiant;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }
}
