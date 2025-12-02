<?php

namespace App\Domain\Reservation;

class Reservation
{
    const STATUT_EN_ATTENTE = 'EN_ATTENTE';
    const STATUT_CONFIRMEE  = 'CONFIRMEE';
    const STATUT_TERMINEE   = 'TERMINEE';
    const STATUT_ANNULEE    = 'ANNULEE';

    private ?int $id;
    private int $utilisateurId;
    private int $parkingId;
    private \DateTimeImmutable $debut;
    private \DateTimeImmutable $fin;
    private string $statut;
    private ?float $prixEstime;
    private ?float $prixFinal;
    private ?float $penalite;
    private \DateTimeImmutable $creeLe;

    public function __construct(
        ?int $id,
        int $utilisateurId,
        int $parkingId,
        \DateTimeImmutable $debut,
        \DateTimeImmutable $fin,
        string $statut = self::STATUT_CONFIRMEE,
        ?float $prixEstime = null,
        ?float $prixFinal = null,
        ?float $penalite = null,
        ?\DateTimeImmutable $creeLe = null
    ) {
        if ($fin <= $debut) {
            throw new \InvalidArgumentException('La date de fin doit être après la date de début.');
        }

        if (!in_array($statut, self::statutsValides(), true)) {
            throw new \InvalidArgumentException("Statut invalide : {$statut}");
        }

        $this->id = $id;
        $this->utilisateurId = $utilisateurId;
        $this->parkingId = $parkingId;
        $this->debut = $debut;
        $this->fin = $fin;
        $this->statut = $statut;
        $this->prixEstime = $prixEstime;
        $this->prixFinal = $prixFinal;
        $this->penalite = $penalite;
        $this->creeLe = $creeLe ?? new \DateTimeImmutable();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUtilisateurId()
    {
        return $this->utilisateurId;
    }

    public function getParkingId()
    {
        return $this->parkingId;
    }

    public function getDebut()
    {
        return $this->debut;
    }

    public function getFin()
    {
        return $this->fin;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function getPrixEstime()
    {
        return $this->prixEstime;
    }

    public function getPrixFinal()
    {
        return $this->prixFinal;
    }

    public function getPenalite()
    {
        return $this->penalite;
    }

    public function getCreeLe()
    {
        return $this->creeLe;
    }

    public function terminer($prixFinal, $penalite = null)
    {
        $this->statut = self::STATUT_TERMINEE;
        $this->prixFinal = $prixFinal;
        $this->penalite = $penalite;
    }

    public function annuler()
    {
        if ($this->statut === self::STATUT_TERMINEE) {
            throw new \RuntimeException('Impossible d’annuler une réservation terminée.');
        }

        $this->statut = self::STATUT_ANNULEE;
    }

    public function estActiveA(\DateTimeImmutable $moment)
    {
        return $moment >= $this->debut
            && $moment <= $this->fin
            && in_array($this->statut, [self::STATUT_EN_ATTENTE, self::STATUT_CONFIRMEE], true);
    }

    public static function statutsValides()
    {
        return [
            self::STATUT_EN_ATTENTE,
            self::STATUT_CONFIRMEE,
            self::STATUT_TERMINEE,
            self::STATUT_ANNULEE,
        ];
    }
}