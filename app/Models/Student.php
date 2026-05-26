<?php

namespace App\Models;

/**
 * Student - Classe pour les étudiants
 * 
 * Extends User avec :
 * - file_number, enrollment_date
 * - global_status
 */
class Student extends User
{
    protected string $table = 'STUDENT';
    protected string $file_number = '';
    protected string $enrollment_date = '';
    protected string $global_status = 'Applicant';

    /**
     * Constructeur
     */
    public function __construct(?\PDO $pdo = null, string $first_name = '', string $last_name = '', string $email = '', string $file_number = '')
    {
        parent::__construct($pdo, $first_name, $last_name, $email);
        $this->file_number = $file_number;
        $this->enrollment_date = date('Y-m-d');
    }

    /**
     * Getters
     */
    public function getFileNumber(): string
    {
        return $this->file_number;
    }

    public function getEnrollmentDate(): string
    {
        return $this->enrollment_date;
    }

    public function getGlobalStatus(): string
    {
        return $this->global_status;
    }

    /**
     * Setters
     */
    public function setFileNumber(string $value): void
    {
        $this->file_number = $value;
    }

    public function setEnrollmentDate(string $value): void
    {
        $this->enrollment_date = $value;
    }

    public function setGlobalStatus(string $value): void
    {
        $this->global_status = $value;
    }

    /**
     * Valider l'étudiant
     */
    public function validate(): bool
    {
        // Valider d'abord les données du parent (User)
        if (!parent::validate()) {
            return false;
        }

        // Valider le numéro de dossier
        if (empty($this->file_number)) {
            $this->addError('Le numéro de dossier est requis');
        }

        // Valider la date d'inscription
        if (empty($this->enrollment_date)) {
            $this->addError('La date d\'inscription est requise');
        }

        return count($this->errors) === 0;
    }

    /**
     * Afficher les infos de l'étudiant
     */
    public function getInfo(): string
    {
        return sprintf(
            "Étudiant: %s (%s) - Status: %s",
            $this->getFullName(),
            $this->file_number,
            $this->global_status
        );
    }

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
            $sql = "INSERT INTO {$this->table} (first_name, last_name, email, file_number, enrollment_date, global_status)
                    VALUES (:first_name, :last_name, :email, :file_number, :enrollment_date, :global_status)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':first_name' => $this->first_name,
                ':last_name' => $this->last_name,
                ':email' => $this->email,
                ':file_number' => $this->file_number,
                ':enrollment_date' => $this->enrollment_date,
                ':global_status' => $this->global_status,
            ]);

            return true;
        } catch (\Exception $e) {
            $this->addError('Erreur lors de la sauvegarde : ' . $e->getMessage());
            return false;
        }
    }

    public static function findAll(\PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM STUDENT ORDER BY id DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM STUDENT WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function updateById(\PDO $pdo, int $id, array $data): bool
    {
        $sql = 'UPDATE STUDENT SET first_name = :first_name, last_name = :last_name, email = :email,
                file_number = :file_number, enrollment_date = :enrollment_date, global_status = :global_status
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':file_number' => $data['file_number'],
            ':enrollment_date' => $data['enrollment_date'],
            ':global_status' => $data['global_status'],
        ]);
    }

    public static function deleteById(\PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM STUDENT WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
