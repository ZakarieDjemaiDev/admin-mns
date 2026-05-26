<?php

namespace App\Models;

/**
 * BaseModel - Classe abstraite parent de tous les models
 * 
 * Fournit :
 * - Gestion de la BDD (PDO)
 * - Méthodes abstraites que tous les models doivent implémenter
 * - Validation de base
 */
abstract class BaseModel
{
    protected ?\PDO $pdo;
    protected string $table;
    protected array $errors = [];
    protected array $attributes = [];

    public function __construct(?\PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Valider les données (à implémenter dans les enfants)
     */
    abstract public function validate(): bool;

    /**
     * Sauvegarder en BDD (à implémenter dans les enfants)
     */
    abstract public function save(): bool;

    /**
     * Récupérer les erreurs de validation
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajouter une erreur
     */
    protected function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Récupérer un attribut
     */
    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Définir un attribut
     */
    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Vérifier si un attribut existe
     */
    public function hasAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Récupérer tous les attributs
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
