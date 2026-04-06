<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource; // Import grâce à API Platform

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource] // Création d'une automatique d'une interface API
class Task { // Entité Tâche

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // Attribut ID
 
    #[ORM\Column(length: 50)]
    private ?string $title = null; // Attribut Titre (longueur 50 caractères max)

    #[ORM\Column(length: 255)]
    private ?string $description = null; // Attribut Description (longueur 255 caractères max)

    #[ORM\Column(length: 30)]
    private ?string $status = null; // Attribut Statut (longueur 30 caractères max)

    public function getId(): ?int // Getter pour l'ID
    {
        return $this->id;
    }

    public function getTitle(): ?string // Getter pour le titre
    {
        return $this->title;
    }

    public function setTitle(string $title): static // Setter pour le titre
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string // Getter pour la description
    {
        return $this->description;
    }

    public function setDescription(string $description): static // Setter pour la description
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string // Getter pour le statut
    {
        return $this->status;
    }

    public function setStatus(string $status): static // Setter pour le statut
    {
        $this->status = $status;

        return $this;
    }
}
