<?php

namespace App\Entity;

use App\Repository\AutorisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutorisationRepository::class)
 */
class Autorisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $lecture;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ecriture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLecture(): ?int
    {
        return $this->lecture;
    }

    public function setLecture(int $lecture): self
    {
        $this->lecture = $lecture;

        return $this;
    }

    public function getEcriture(): ?int
    {
        return $this->ecriture;
    }

    public function setEcriture(int $ecriture): self
    {
        $this->ecriture = $ecriture;

        return $this;
    }
}
