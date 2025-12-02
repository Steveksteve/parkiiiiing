<?php
namespace Domain\User;

enum UserRole: string
{
    case DRIVER = 'driver';      // simple utilisateur
    case OWNER = 'owner';        // propriétaire de parkings
    case ADMIN = 'admin';        // optionnel
}
