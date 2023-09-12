<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $ref_mat = null;

    #[ORM\Column(length: 25)]
    private ?string $nom_mat = null;

    #[ORM\OneToMany(mappedBy: 'mat', targetEntity: Document::class)]
    private Collection $id_mat;

    public function __construct()
    {
        $this->id_mat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefMat(): ?string
    {
        return $this->ref_mat;
    }

    public function setRefMat(string $ref_mat): static
    {
        $this->ref_mat = $ref_mat;

        return $this;
    }

    public function getNomMat(): ?string
    {
        return $this->nom_mat;
    }

    public function setNomMat(string $nom_mat): static
    {
        $this->nom_mat = $nom_mat;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getIdMat(): Collection
    {
        return $this->id_mat;
    }

    public function addIdMat(Document $idMat): static
    {
        if (!$this->id_mat->contains($idMat)) {
            $this->id_mat->add($idMat);
            $idMat->setMat($this);
        }

        return $this;
    }

    public function removeIdMat(Document $idMat): static
    {
        if ($this->id_mat->removeElement($idMat)) {
            // set the owning side to null (unless already changed)
            if ($idMat->getMat() === $this) {
                $idMat->setMat(null);
            }
        }

        return $this;
    }
}
