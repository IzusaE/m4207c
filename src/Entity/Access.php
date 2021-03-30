<?php

namespace App\Entity;

use App\Repository\AccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccessRepository::class)
 */
class Access
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(nullable=false)
=======
     * @ORM\Column(type="integer")
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
     */
    private $utilisateur_id_id;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity=Autorisation::class)
     * @ORM\JoinColumn(nullable=false)
=======
     * @ORM\Column(type="integer")
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
     */
    private $autorisation_id_id;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity=Document::class)
     * @ORM\JoinColumn(nullable=false)
=======
     * @ORM\Column(type="integer")
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
     */
    private $document_id_id;

    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getUtilisateurIdId(): ?Utilisateur
=======
    public function getUtilisateurIdId(): ?User
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
    {
        return $this->utilisateur_id_id;
    }

<<<<<<< HEAD
    public function setUtilisateurIdId(?Utilisateur $utilisateur_id_id): self
=======
    public function setUtilisateurIdId(?User $Utilisateur_id_id): self
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
    {
        $this->utilisateur_id_id = $utilisateur_id_id;

        return $this;
    }

    public function getAutorisationIdId(): ?Autorisation
    {
        return $this->autorisation_id_id;
    }

    public function setAutorisationIdId(?Autorisation $autorisation_id_id): self
    {
        $this->autorisation_id_id = $autorisation_id_id;

        return $this;
    }

    public function getDocumentIdId(): ?Document
    {
        return $this->document_id_id;
    }

    public function setDocumentIdId(?Document $document_id_id): self
    {
        $this->document_id_id = $document_id_id;

        return $this;
    }
}
