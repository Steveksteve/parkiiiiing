<?php
namespace Domain\User;

use Domain\Reservation\Reservation;
use Domain\Parking\Parking;
use Domain\Stationnement\Stationnement;

class User
{
  private ?int $id;
  private string $email;
  private string $passwordHash;
  private string $firstName;
  private string $lastName;
  // private UserRole $role;

  /** @var Reservation[] */
  private array $reservations = [];

  /** @var Stationnement[] */
  private array $stationnements = [];

  /** @var Parking[]  Propriétaire seulement */
  private array $ownedParkings = [];

  public function __construct(
    ?int $id,
    string $email,
    string $passwordHash,
    string $firstName,
    string $lastName,
    // UserRole $role
  ) {
    // Ici tu peux déjà faire quelques validations simples
    $this->id = $id;
    $this->email = $email;
    $this->passwordHash = $passwordHash;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    // $this->role = $role;
  }

  // --- Getters ---

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getPasswordHash(): string
  {
    return $this->passwordHash;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  // public function getRole(): UserRole
  // {
  //     return $this->role;
  // }

  /** @return Reservation[] */
  public function getReservations(): array
  {
    return $this->reservations;
  }

  /** @return Stationnement[] */
  public function getStationnements(): array
  {
    return $this->stationnements;
  }

  /** @return Parking[] */
  public function getOwnedParkings(): array
  {
    return $this->ownedParkings;
  }

  // --- Méthodes métier basiques ---

  // public function addReservation(Reservation $reservation): void
  // {
  //     $this->reservations[] = $reservation;
  // }

  // public function addStationnement(Stationnement $stationnement): void
  // {
  //     $this->stationnements[] = $stationnement;
  // }

  // public function addOwnedParking(Parking $parking): void
  // {
  //     if ($this->role !== UserRole::OWNER) {
  //         throw new \LogicException('Seul un propriétaire peut posséder des parkings.');
  //     }

  //     $this->ownedParkings[] = $parking;
  // }

  public function changePassword(string $newPasswordHash): void
  {
    $this->passwordHash = $newPasswordHash;
  }
}
