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
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $utilisateurId;

    /**
     * @ORM\ManyToOne(targetEntity=Autorisation::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $autorisationId;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $documentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurId(): ?Utilisateur
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?Utilisateur $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;

        return $this;
    }

    public function getAutorisationId(): ?Autorisation
    {
        return $this->autorisationId;
    }

    public function setAutorisationId(?Autorisation $autorisationId): self
    {
        $this->autorisationId = $autorisationId;

        return $this;
    }

    public function getDocumentId(): ?Document
    {
        return $this->documentId;
    }

    public function setDocumentId(?Document $documentId): self
    {
        $this->documentId = $documentId;

        return $this;
    }
}
