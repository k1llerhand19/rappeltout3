<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $ref_doc = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin_valid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_doc = null;

    #[ORM\ManyToOne(inversedBy: 'id_mat')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $mat = null;

    #[ORM\Column(length: 200)]
    private ?string $Titre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefDoc(): ?string
    {
        return $this->ref_doc;
    }

    public function setRefDoc(string $ref_doc): static
    {
        $this->ref_doc = $ref_doc;

        return $this;
    }

    public function getDateFinValid(): ?\DateTimeInterface
    {
        return $this->date_fin_valid;
    }

    public function setDateFinValid(\DateTimeInterface $date_fin_valid): static
    {
        $this->date_fin_valid = $date_fin_valid;

        return $this;
    }

    public function getDateDoc(): ?\DateTimeInterface
    {
        return $this->date_doc;
    }

    public function setDateDoc(\DateTimeInterface $date_doc): static
    {
        $this->date_doc = $date_doc;

        return $this;
    }

    public function getMat(): ?Materiel
    {
        return $this->mat;
    }

    public function setMat(?Materiel $mat): static
    {
        $this->mat = $mat;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }
}
