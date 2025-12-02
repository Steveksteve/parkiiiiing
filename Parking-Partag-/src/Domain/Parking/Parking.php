<?php

namespace App\Domain\Parking;

class Parking
{
    private ?int $id;
    private int $proprietaireId;
    private string $nom;
    private float $latitude;
    private float $longitude;
    private int $capacite;
    private \DateTimeImmutable $creeLe;
    private \DateTimeImmutable $modifieLe;

    public function __construct(
        ?int $id,
        int $proprietaireId,
        string $nom,
        float $latitude,
        float $longitude,
        int $capacite,
        ?\DateTimeImmutable $creeLe = null,
        ?\DateTimeImmutable $modifieLe = null
    ) {
        $nom = trim($nom);

        if ($nom === '') {
            throw new \InvalidArgumentException('Le nom du parking ne peut pas être vide.');
        }

        if ($capacite <= 0) {
            throw new \InvalidArgumentException('La capacité doit être supérieure à 0.');
        }

        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Latitude invalide.');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Longitude invalide.');
        }

        $this->id = $id;
        $this->proprietaireId = $proprietaireId;
        $this->nom = $nom;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->capacite = $capacite;
        $this->creeLe = $creeLe ?? new \DateTimeImmutable();
        $this->modifieLe = $modifieLe ?? $this->creeLe;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProprietaireId()
    {
        return $this->proprietaireId;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function getDateCreation()
    {
        return $this->creeLe;
    }

    public function getDateModification()
    {
        return $this->modifieLe;
    }

    public function changerNom($nouveauNom)
    {
        $nouveauNom = trim($nouveauNom);

        if ($nouveauNom === '') {
            throw new \InvalidArgumentException('Le nom ne peut pas être vide.');
        }

        $this->nom = $nouveauNom;
        $this->toucher();
    }

    public function changerCapacite($nouvelleCapacite)
    {
        if ($nouvelleCapacite <= 0) {
            throw new \InvalidArgumentException('La capacité doit être supérieure à 0.');
        }

        $this->capacite = $nouvelleCapacite;
        $this->toucher();
    }

    public function changerLocalisation($latitude, $longitude)
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Latitude invalide.');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Longitude invalide.');
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->toucher();
    }

    private function toucher()
    {
        $this->modifieLe = new \DateTimeImmutable();
    }
}