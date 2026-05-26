<?php

namespace App\Models;

/**
 * Training - Classe pour les formations
 * 
 * Propriétés :
 * - training_name, description
 * - duration_months, start_date, end_date
 * - total_places, is_active
 */
class Training extends BaseModel
{
    protected string $table = 'TRAINING';
    protected string $training_name = '';
    protected string $description = '';
    protected int $duration_months = 0;
    protected string $start_date = '';
    protected string $end_date = '';
    protected int $total_places = 0;
    protected bool $is_active = true;

    /**
     * Constructeur
     */
    public function __construct(?\PDO $pdo = null, string $training_name = '', int $duration_months = 0)
    {
        parent::__construct($pdo);
        $this->training_name = $training_name;
        $this->duration_months = $duration_months;
    }

    /**
     * Getters
     */
    public function getTrainingName(): string
    {
        return $this->training_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDurationMonths(): int
    {
        return $this->duration_months;
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function getEndDate(): string
    {
        return $this->end_date;
    }

    public function getTotalPlaces(): int
    {
        return $this->total_places;
    }

    public function getInfo(): string
    {
        return sprintf(
            "%s (%d mois) - Places: %d",
            $this->training_name,
            $this->duration_months,
            $this->total_places
        );
    }

    /**
     * Setters
     */
    public function setTrainingName(string $value): void
    {
        $this->training_name = $value;
    }

    public function setDescription(string $value): void
    {
        $this->description = $value;
    }

    public function setDurationMonths(int $value): void
    {
        $this->duration_months = $value;
    }

    public function setStartDate(string $value): void
    {
        $this->start_date = $value;
        // Calculer la date de fin automatiquement
        if (!empty($value) && $this->duration_months > 0) {
            $start = \DateTime::createFromFormat('Y-m-d', $value);
            $start->modify("+{$this->duration_months} months");
            $this->end_date = $start->format('Y-m-d');
        }
    }

    public function setTotalPlaces(int $value): void
    {
        $this->total_places = $value;
    }

    /**
     * Calculer automatiquement la date de fin
     */
    public function calculateEndDate(): string
    {
        if (!empty($this->start_date) && $this->duration_months > 0) {
            $start = \DateTime::createFromFormat('Y-m-d', $this->start_date);
            $start->modify("+{$this->duration_months} months");
            return $start->format('Y-m-d');
        }
        return '';
    }

    /**
     * Valider la formation
     */
    public function validate(): bool
    {
        $this->errors = [];

        if (empty($this->training_name)) {
            $this->addError('Le nom de la formation est requis');
        }

        if ($this->duration_months <= 0) {
            $this->addError('La durée doit être supérieure à 0 mois');
        }

        if ($this->total_places <= 0) {
            $this->addError('Le nombre de places doit être supérieur à 0');
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            if (strtotime($this->start_date) >= strtotime($this->end_date)) {
                $this->addError('La date de début doit être antérieure à la date de fin');
            }
        }

        return count($this->errors) === 0;
    }

    /**
     * Sauvegarder la formation
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
            // Calculer la date de fin si nécessaire
            $endDate = !empty($this->end_date) ? $this->end_date : $this->calculateEndDate();

            $sql = "INSERT INTO {$this->table} (training_name, description, duration_months, start_date, end_date, total_places, is_active) 
                    VALUES (:training_name, :description, :duration_months, :start_date, :end_date, :total_places, :is_active)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':training_name' => $this->training_name,
                ':description' => $this->description ?: null,
                ':duration_months' => $this->duration_months,
                ':start_date' => !empty($this->start_date) ? $this->start_date : null,
                ':end_date' => !empty($endDate) ? $endDate : null,
                ':total_places' => $this->total_places,
                ':is_active' => $this->is_active ? 1 : 0
            ]);

            return true;
        } catch (\Exception $e) {
            $this->addError('Erreur lors de la sauvegarde : ' . $e->getMessage());
            return false;
        }
    }

    public static function findAll(\PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM TRAINING ORDER BY id DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM TRAINING WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function updateById(\PDO $pdo, int $id, array $data): bool
    {
        $endDate = '';
        if (!empty($data['start_date']) && $data['duration_months'] > 0) {
            $start = \DateTime::createFromFormat('Y-m-d', $data['start_date']);
            if ($start) {
                $start->modify('+' . $data['duration_months'] . ' months');
                $endDate = $start->format('Y-m-d');
            }
        }

        $sql = 'UPDATE TRAINING SET training_name = :training_name, description = :description,
                duration_months = :duration_months, start_date = :start_date, end_date = :end_date,
                total_places = :total_places, is_active = :is_active WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':training_name' => $data['training_name'],
            ':description' => $data['description'] ?: null,
            ':duration_months' => $data['duration_months'],
            ':start_date' => $data['start_date'] ?: null,
            ':end_date' => $endDate ?: null,
            ':total_places' => $data['total_places'],
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }

    public static function deleteById(\PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM TRAINING WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
