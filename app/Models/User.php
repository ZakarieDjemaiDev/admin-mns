<?php

namespace App\Models;

/**
 * User - Classe parent pour tous les utilisateurs
 * 
 * Propriétés :
 * - first_name, last_name, email
 * - role, school_id
 * - is_active
 */
class User extends BaseModel
{
    protected string $table = 'USER';
    protected string $first_name = '';
    protected string $last_name = '';
    protected string $email = '';
    protected string $password = '';
    protected string $role = '';
    protected int $school_id = 0;
    protected bool $is_active = true;

    /**
     * Constructeur
     */
    public function __construct(?\PDO $pdo = null, string $first_name = '', string $last_name = '', string $email = '')
    {
        parent::__construct($pdo);
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
    }

    /**
     * Getters
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Setters
     */
    public function setFirstName(string $value): void
    {
        $this->first_name = $value;
    }

    public function setLastName(string $value): void
    {
        $this->last_name = $value;
    }

    public function setEmail(string $value): void
    {
        $this->email = $value;
    }

    public function setPassword(string $value): void
    {
        $this->password = password_hash($value, PASSWORD_DEFAULT);
    }

    public function setRole(string $value): void
    {
        $this->role = $value;
    }

    /**
     * Valider les données
     */
    public function validate(): bool
    {
        $this->errors = [];

        // Vérifier le prénom
        if (empty($this->first_name)) {
            $this->addError('Le prénom est requis');
        }

        // Vérifier le nom
        if (empty($this->last_name)) {
            $this->addError('Le nom est requis');
        }

        // Vérifier l'email
        if (empty($this->email)) {
            $this->addError('L\'email est requis');
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('L\'email est invalide');
        }

        return count($this->errors) === 0;
    }

    /**
     * Sauvegarder l'utilisateur
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->pdo === null) {
            $this->addError('Connexion base de données indisponible');
            return false;
        }

        try {
            $sql = "INSERT INTO {$this->table} (first_name, last_name, email, password, is_active) 
                    VALUES (:first_name, :last_name, :email, :password, :is_active)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':first_name' => $this->first_name,
                ':last_name' => $this->last_name,
                ':email' => $this->email,
                ':password' => $this->password ?: '',
                ':is_active' => $this->is_active ? 1 : 0
            ]);

            return true;
        } catch (\Exception $e) {
            $this->addError('Erreur lors de la sauvegarde : ' . $e->getMessage());
            return false;
        }
    }
}
