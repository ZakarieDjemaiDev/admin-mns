<?php

namespace App\Services;

/**
 * Stockage en session lorsque la BDD n'est pas disponible.
 */
class DemoStore
{
    private const SESSION_KEY = 'admin_mns_demo_data';

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [
                'students' => self::defaultStudents(),
                'trainings' => self::defaultTrainings(),
                'absences' => self::defaultAbsences(),
                'retards' => self::defaultRetards(),
            ];
            return;
        }

        if (!isset($_SESSION[self::SESSION_KEY]['absences'])) {
            $_SESSION[self::SESSION_KEY]['absences'] = self::defaultAbsences();
        }
        if (!isset($_SESSION[self::SESSION_KEY]['retards'])) {
            $_SESSION[self::SESSION_KEY]['retards'] = self::defaultRetards();
        }
    }

    public static function students(): array
    {
        self::init();
        return $_SESSION[self::SESSION_KEY]['students'];
    }

    public static function saveStudent(array $student): void
    {
        self::init();
        $students = &$_SESSION[self::SESSION_KEY]['students'];

        if (!empty($student['id'])) {
            foreach ($students as $i => $row) {
                if ((int) $row['id'] === (int) $student['id']) {
                    $students[$i] = array_merge($row, $student);
                    return;
                }
            }
        }

        $student['id'] = self::nextId($students);
        $students[] = $student;
    }

    public static function deleteStudent(int $id): void
    {
        self::init();
        $_SESSION[self::SESSION_KEY]['students'] = array_values(array_filter(
            self::students(),
            fn($s) => (int) $s['id'] !== $id
        ));
    }

    public static function findStudent(int $id): ?array
    {
        foreach (self::students() as $student) {
            if ((int) $student['id'] === $id) {
                return $student;
            }
        }
        return null;
    }

    public static function trainings(): array
    {
        self::init();
        return $_SESSION[self::SESSION_KEY]['trainings'];
    }

    public static function saveTraining(array $training): void
    {
        self::init();
        $trainings = &$_SESSION[self::SESSION_KEY]['trainings'];

        if (!empty($training['id'])) {
            foreach ($trainings as $i => $row) {
                if ((int) $row['id'] === (int) $training['id']) {
                    $trainings[$i] = array_merge($row, $training);
                    return;
                }
            }
        }

        $training['id'] = self::nextId($trainings);
        $trainings[] = $training;
    }

    public static function deleteTraining(int $id): void
    {
        self::init();
        $_SESSION[self::SESSION_KEY]['trainings'] = array_values(array_filter(
            self::trainings(),
            fn($t) => (int) $t['id'] !== $id
        ));
    }

    public static function findTraining(int $id): ?array
    {
        foreach (self::trainings() as $training) {
            if ((int) $training['id'] === $id) {
                return $training;
            }
        }
        return null;
    }

    public static function absences(): array
    {
        self::init();
        return $_SESSION[self::SESSION_KEY]['absences'];
    }

    public static function saveAbsence(array $absence): void
    {
        self::init();
        $absences = &$_SESSION[self::SESSION_KEY]['absences'];

        if (!empty($absence['id'])) {
            foreach ($absences as $i => $row) {
                if ((int) $row['id'] === (int) $absence['id']) {
                    $absences[$i] = array_merge($row, $absence);
                    return;
                }
            }
        }

        $absence['id'] = self::nextId($absences);
        $absences[] = $absence;
    }

    public static function deleteAbsence(int $id): void
    {
        self::init();
        $_SESSION[self::SESSION_KEY]['absences'] = array_values(array_filter(
            self::absences(),
            fn($a) => (int) $a['id'] !== $id
        ));
    }

    public static function findAbsence(int $id): ?array
    {
        foreach (self::absences() as $absence) {
            if ((int) $absence['id'] === $id) {
                return $absence;
            }
        }
        return null;
    }

    public static function retards(): array
    {
        self::init();
        return $_SESSION[self::SESSION_KEY]['retards'];
    }

    public static function saveRetard(array $retard): void
    {
        self::init();
        $retards = &$_SESSION[self::SESSION_KEY]['retards'];

        if (!empty($retard['id'])) {
            foreach ($retards as $i => $row) {
                if ((int) $row['id'] === (int) $retard['id']) {
                    $retards[$i] = array_merge($row, $retard);
                    return;
                }
            }
        }

        $retard['id'] = self::nextId($retards);
        $retards[] = $retard;
    }

    public static function deleteRetard(int $id): void
    {
        self::init();
        $_SESSION[self::SESSION_KEY]['retards'] = array_values(array_filter(
            self::retards(),
            fn($r) => (int) $r['id'] !== $id
        ));
    }

    public static function findRetard(int $id): ?array
    {
        foreach (self::retards() as $retard) {
            if ((int) $retard['id'] === $id) {
                return $retard;
            }
        }
        return null;
    }

    private static function nextId(array $rows): int
    {
        $max = 0;
        foreach ($rows as $row) {
            $max = max($max, (int) ($row['id'] ?? 0));
        }
        return $max + 1;
    }

    private static function defaultStudents(): array
    {
        return [
            [
                'id' => 1,
                'first_name' => 'Alice',
                'last_name' => 'Durand',
                'email' => 'alice@example.com',
                'file_number' => 'STU-2025-001',
                'enrollment_date' => '2025-09-01',
                'global_status' => 'Enrolled',
            ],
            [
                'id' => 2,
                'first_name' => 'Bob',
                'last_name' => 'Martin',
                'email' => 'bob@example.com',
                'file_number' => 'STU-2025-002',
                'enrollment_date' => '2025-09-15',
                'global_status' => 'Applicant',
            ],
            [
                'id' => 3,
                'first_name' => 'Claire',
                'last_name' => 'Bernard',
                'email' => 'claire@example.com',
                'file_number' => 'STU-2025-003',
                'enrollment_date' => '2024-01-10',
                'global_status' => 'Graduated',
            ],
        ];
    }

    private static function defaultTrainings(): array
    {
        return [
            [
                'id' => 1,
                'training_name' => 'Developpement Web Full Stack',
                'description' => 'HTML, CSS, JavaScript, PHP et bases de donnees.',
                'duration_months' => 12,
                'start_date' => '2025-09-01',
                'end_date' => '2026-09-01',
                'total_places' => 25,
                'is_active' => 1,
            ],
            [
                'id' => 2,
                'training_name' => 'Administration Systemes',
                'description' => 'Linux, reseaux et virtualisation.',
                'duration_months' => 9,
                'start_date' => '2026-01-15',
                'end_date' => '2026-10-15',
                'total_places' => 18,
                'is_active' => 1,
            ],
        ];
    }

    private static function defaultAbsences(): array
    {
        return [
            [
                'id' => 1,
                'student_id' => 2,
                'date' => '2026-05-20',
                'reason' => 'Maladie',
                'justified' => 1,
            ],
            [
                'id' => 2,
                'student_id' => 1,
                'date' => '2026-05-22',
                'reason' => 'Rendez-vous medical',
                'justified' => 0,
            ],
            [
                'id' => 3,
                'student_id' => 3,
                'date' => '2026-05-15',
                'reason' => 'Transport',
                'justified' => 1,
            ],
        ];
    }

    private static function defaultRetards(): array
    {
        return [
            [
                'id' => 1,
                'student_id' => 1,
                'date' => '2026-05-21',
                'minutes_late' => 15,
                'reason' => 'Transports',
            ],
            [
                'id' => 2,
                'student_id' => 2,
                'date' => '2026-05-19',
                'minutes_late' => 30,
            ],
            [
                'id' => 3,
                'student_id' => 1,
                'date' => '2026-05-26',
                'minutes_late' => 10,
                'reason' => 'Reveil difficile',
            ],
        ];
    }
}
