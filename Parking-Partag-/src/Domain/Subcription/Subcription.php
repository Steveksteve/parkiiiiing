<?php

namespace Domain\Subscription;

/**
 * Représente l'abonnement actif (ou passé) d'un utilisateur à un plan spécifique.
 */
class Subscription
{
    // Statuts possibles pour éviter les "magic strings" dans le code
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_EXPIRED = 'EXPIRED';

    public function __construct(
        private ?int $id,
        private int $userId,
        private int $parkingId,
        private int $planId,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private string $status = self::STATUS_ACTIVE
    ) {}

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

    public function getPlanId(): int 
    { 
        return $this->planId; 
    }

    public function getStartDate(): \DateTimeImmutable 
    { 
        return $this->startDate; 
    }

    public function getEndDate(): \DateTimeImmutable 
    { 
        return $this->endDate; 
    }

    public function getStatus(): string 
    { 
        return $this->status; 
    }

    /**
     * Vérifie si l'abonnement est valide à l'instant T.
     * Un abonnement est actif si son statut est ACTIVE et que la date actuelle est comprise dans la période.
     */
    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();

        return $this->status === self::STATUS_ACTIVE 
            && $now >= $this->startDate 
            && $now <= $this->endDate;
    }

    /**
     * Annule l'abonnement (ex: demande de résiliation).
     */
    public function cancel(): void
    {
        $this->status = self::STATUS_CANCELLED;
    }
}