<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity=Genre::class)
     * @ORM\JoinColumn(nullable=false)
=======
     * @ORM\Column(type="integer")
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
     */
    private $type_id_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $chemin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $actif;

    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getTypeIdId(): ?Genre
=======
    public function getTypeIdId(): ?int
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
    {
        return $this->type_id_id;
    }

<<<<<<< HEAD
    public function setTypeIdId(?Genre $type_id_id): self
=======
    public function setTypeIdId(int $type_id_id): self
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
    {
        $this->type_id_id = $type_id_id;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): self
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getActif(): ?int
    {
        return $this->actif;
    }

    public function setActif(int $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}
