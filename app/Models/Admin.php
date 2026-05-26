<?php

namespace App\Models;

/**
 * Admin - Classe pour les administrateurs
 * 
 * Extends User avec :
 * - permissions (array)
 */
class Admin extends User
{
    protected array $permissions = [];

    /**
     * Constructeur
     */
    public function __construct(?\PDO $pdo = null, string $first_name = '', string $last_name = '', string $email = '')
    {
        parent::__construct($pdo, $first_name, $last_name, $email);
        $this->role = 'Admin';
    }

    /**
     * Ajouter une permission
     */
    public function addPermission(string $permission): void
    {
        if (!in_array($permission, $this->permissions)) {
            $this->permissions[] = $permission;
        }
    }

    /**
     * Supprimer une permission
     */
    public function removePermission(string $permission): void
    {
        $key = array_search($permission, $this->permissions);
        if ($key !== false) {
            unset($this->permissions[$key]);
        }
    }

    /**
     * Vérifier si on a une permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }

    /**
     * Récupérer toutes les permissions
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Vérifier si admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    /**
     * Afficher les infos d'admin
     */
    public function getInfo(): string
    {
        $perms = implode(', ', $this->permissions ?: ['Aucune']);
        return sprintf(
            "Admin: %s - Permissions: %s",
            $this->getFullName(),
            $perms
        );
    }
}
