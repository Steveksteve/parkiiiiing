<?php

namespace Domain\Subscription;

/**
 * Interface définissant les méthodes de persistance pour les abonnements.
 * Cette interface permet de découpler le domaine de la base de données (SQL, JSON, etc.).
 */
interface SubscriptionRepositoryInterface
{
    // ==========================================
    // Gestion des Plans (Offres)
    // ==========================================

    /**
     * Sauvegarde ou met à jour un plan d'abonnement.
     */
    public function savePlan(SubscriptionPlan $plan): void;

    /**
     * Trouve un plan par son identifiant.
     */
    public function findPlanById(int $id): ?SubscriptionPlan;

    /**
     * Récupère tous les plans disponibles pour un parking donné.
     * @return SubscriptionPlan[]
     */
    public function findPlansByParking(int $parkingId): array;


    // ==========================================
    // Gestion des Souscriptions (Utilisateurs)
    // ==========================================

    /**
     * Sauvegarde ou met à jour une souscription utilisateur.
     */
    public function save(Subscription $subscription): void;

    /**
     * Récupère l'historique des abonnements d'un utilisateur.
     * @return Subscription[]
     */
    public function findByUser(int $userId): array;

    /**
     * Récupère les abonnements actifs pour un parking à une date donnée.
     * Essentiel pour calculer la capacité restante du parking.
     * * @param int $parkingId
     * @param \DateTimeImmutable $date
     * @return Subscription[]
     */
    public function findActiveSubscriptionsForParking(int $parkingId, \DateTimeImmutable $date): array;
}