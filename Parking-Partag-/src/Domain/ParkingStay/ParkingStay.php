<?php

namespace App\Domain\ParkingStay;

class ParkingStay
{
    private ?int $id;
    private int $userId;
    private int $parkingId;
    private ?int $reservationId;
    private ?int $subscriptionId;
    private \DateTimeImmutable $entryTime;
    private ?\DateTimeImmutable $exitTime;
    private ?int $billedMinutes;
    private ?float $billedAmount;
    private ?float $penaltyAmount;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        ?int $id,
        int $userId,
        int $parkingId,
        \DateTimeImmutable $entryTime,
        ?int $reservationId = null,
        ?int $subscriptionId = null,
        ?\DateTimeImmutable $exitTime = null,
        ?int $billedMinutes = null,
        ?float $billedAmount = null,
        ?float $penaltyAmount = null,
        ?\DateTimeImmutable $createdAt = null
    ) {

        $this->id = $id;
        $this->userId = $userId;
        $this->parkingId = $parkingId;
        $this->reservationId = $reservationId;
        $this->subscriptionId = $subscriptionId;
        $this->entryTime = $entryTime;
        $this->exitTime = $exitTime;
        $this->billedMinutes = $billedMinutes;
        $this->billedAmount = $billedAmount;
        $this->penaltyAmount = $penaltyAmount;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getParkingId(): int
    {
        return $this->parkingId;
    }

    public function getReservationId(): ?int
    {
        return $this->reservationId;
    }

    public function getSubscriptionId(): ?int
    {
        return $this->subscriptionId;
    }

    public function getEntryTime(): \DateTimeImmutable
    {
        return $this->entryTime;
    }

    public function getExitTime(): ?\DateTimeImmutable
    {
        return $this->exitTime;
    }

    public function getBilledMinutes(): ?int
    {
        return $this->billedMinutes;
    }

    public function getBilledAmount(): ?float
    {
        return $this->billedAmount;
    }

    public function getPenaltyAmount(): ?float
    {
        return $this->penaltyAmount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function estEnCours(): bool
    {
        return $this->exitTime === null;
    }

    public function ajouterPenalite(float $montantPenalite): void
    {
        $this->penaltyAmount = ($this->penaltyAmount ?? 0) + $montantPenalite;
    }

    public function getMontantTotal(): ?float
    {
        return $this->billedAmount + ($this->penaltyAmount ?? 0);
    }

    public function estLieAReservation(): bool
    {
        return $this->reservationId !== null;
    }

    public function estLieAAbonnement(): bool
    {
        return $this->subscriptionId !== null;
    }
}
