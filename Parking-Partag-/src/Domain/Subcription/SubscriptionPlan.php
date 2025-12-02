<?php

namespace Domain\Subscription;

/**
 * Représente un type d'abonnement proposé par un parking.
 * Exemple : "Forfait Nuit", "Abonnement Mensuel 24/7".
 */
class SubscriptionPlan
{
    public function __construct(
        private ?int $id,
        private int $parkingId,
        private string $name,
        private float $monthlyPrice,
        private ?string $description = null
    ) {}

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getParkingId(): int 
    { 
        return $this->parkingId; 
    }

    public function getName(): string 
    { 
        return $this->name; 
    }

    public function getMonthlyPrice(): float 
    { 
        return $this->monthlyPrice; 
    }

    public function getDescription(): ?string 
    { 
        return $this->description; 
    }
}