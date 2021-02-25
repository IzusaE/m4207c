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
     * @ORM\Column(type="integer")
     */
    private $utilisateur_id_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $autorisation_id_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $document_id_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurIdId(): ?User
    {
        return $this->utilisateur_id_id;
    }

    public function setUtilisateurIdId(?User $Utilisateur_id_id): self
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
