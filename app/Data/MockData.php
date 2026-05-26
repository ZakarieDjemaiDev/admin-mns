<?php

namespace App\Data;

/**
 * Donnees fictives pour la maquette front (pas de BDD).
 */
class MockData
{
    public static function students(): array
    {
        return [
            ['id' => 1, 'first_name' => 'Alice', 'last_name' => 'Durand', 'email' => 'alice@example.com', 'file_number' => 'STU-2025-001', 'enrollment_date' => '2025-09-01', 'global_status' => 'Enrolled'],
            ['id' => 2, 'first_name' => 'Bob', 'last_name' => 'Martin', 'email' => 'bob@example.com', 'file_number' => 'STU-2025-002', 'enrollment_date' => '2025-09-15', 'global_status' => 'Applicant'],
            ['id' => 3, 'first_name' => 'Claire', 'last_name' => 'Bernard', 'email' => 'claire@example.com', 'file_number' => 'STU-2025-003', 'enrollment_date' => '2024-01-10', 'global_status' => 'Graduated'],
        ];
    }

    public static function trainings(): array
    {
        return [
            ['id' => 1, 'training_name' => 'Developpement Web Full Stack', 'description' => 'HTML, CSS, JavaScript, PHP.', 'duration_months' => 12, 'start_date' => '2025-09-01', 'end_date' => '2026-09-01', 'total_places' => 25, 'is_active' => 1],
            ['id' => 2, 'training_name' => 'Administration Systemes', 'description' => 'Linux, reseaux.', 'duration_months' => 9, 'start_date' => '2026-01-15', 'end_date' => '2026-10-15', 'total_places' => 18, 'is_active' => 1],
        ];
    }

    public static function absences(): array
    {
        return [
            ['id' => 1, 'student_id' => 2, 'date' => date('Y-m-20'), 'reason' => 'Maladie', 'justified' => 1],
            ['id' => 2, 'student_id' => 1, 'date' => date('Y-m-22'), 'reason' => 'Rendez-vous medical', 'justified' => 0],
            ['id' => 3, 'student_id' => 3, 'date' => date('Y-m-15'), 'reason' => 'Transport', 'justified' => 1],
        ];
    }

    public static function retards(): array
    {
        return [
            ['id' => 1, 'student_id' => 1, 'date' => date('Y-m-21'), 'minutes_late' => 15, 'reason' => 'Transports'],
            ['id' => 2, 'student_id' => 2, 'date' => date('Y-m-19'), 'minutes_late' => 30, 'reason' => ''],
            ['id' => 3, 'student_id' => 1, 'date' => date('Y-m-d'), 'minutes_late' => 10, 'reason' => 'Reveil difficile'],
        ];
    }

    public static function findStudent(int $id): ?array
    {
        foreach (self::students() as $row) {
            if ((int) $row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }

    public static function findTraining(int $id): ?array
    {
        foreach (self::trainings() as $row) {
            if ((int) $row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }

    public static function findAbsence(int $id): ?array
    {
        foreach (self::absences() as $row) {
            if ((int) $row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }

    public static function findRetard(int $id): ?array
    {
        foreach (self::retards() as $row) {
            if ((int) $row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }
}