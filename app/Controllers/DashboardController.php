<?php

namespace App\Controllers;

use App\Models\Absence;
use App\Models\Retard;
use App\Services\DemoStore;

class DashboardController
{
    public function index(): void
    {
        $pdo = tryDatabase();
        $demoMode = $pdo === null;

        if ($demoMode) {
            $students = DemoStore::students();
            $trainings = DemoStore::trainings();
            $absences = DemoStore::absences();
            $retards = DemoStore::retards();
        } else {
            $students = \App\Models\Student::findAll($pdo);
            $trainings = \App\Models\Training::findAll($pdo);
            $absences = Absence::findAll($pdo);
            $retards = Retard::findAll($pdo);
        }

        $absencesMonth = filterCurrentMonth($absences);
        $retardsMonth = filterCurrentMonth($retards);

        usort($absences, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));
        usort($retards, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));

        $stats = [
            'students' => count($students),
            'trainings' => count($trainings),
            'enrolled' => count(array_filter($students, fn($s) => ($s['global_status'] ?? '') === 'Enrolled')),
            'active_trainings' => count(array_filter($trainings, fn($t) => !empty($t['is_active']))),
            'absences_month' => count($absencesMonth),
            'retards_month' => count($retardsMonth),
        ];

        render('dashboard', [
            'currentPage' => 'dashboard',
            'demoMode' => $demoMode,
            'stats' => $stats,
            'students' => $students,
            'recentStudents' => array_slice($students, 0, 5),
            'recentTrainings' => array_slice($trainings, 0, 5),
            'recentAbsences' => array_slice($absences, 0, 3),
            'recentRetards' => array_slice($retards, 0, 3),
        ], 'Tableau de bord');
    }
}
