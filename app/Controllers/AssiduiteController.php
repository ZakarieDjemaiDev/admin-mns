<?php

namespace App\Controllers;

use App\Models\Absence;
use App\Models\Retard;
use App\Models\Student;
use App\Services\DemoStore;

class AssiduiteController
{
    public function index(): void
    {
        $pdo = tryDatabase();
        $demoMode = $pdo === null;

        if ($demoMode) {
            $students = DemoStore::students();
            $absences = DemoStore::absences();
            $retards = DemoStore::retards();
        } else {
            $students = Student::findAll($pdo);
            $absences = Absence::findAll($pdo);
            $retards = Retard::findAll($pdo);
        }

        $absencesMonth = filterCurrentMonth($absences);
        $retardsMonth = filterCurrentMonth($retards);

        $stats = [
            'absences_month' => count($absencesMonth),
            'retards_month' => count($retardsMonth),
            'unjustified_absences_month' => count(array_filter(
                $absencesMonth,
                fn($a) => empty($a['justified'])
            )),
            'minutes_late_month' => array_sum(array_map(
                fn($r) => (int) ($r['minutes_late'] ?? 0),
                $retardsMonth
            )),
        ];

        usort($absences, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));
        usort($retards, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));

        render('assiduite/index', [
            'currentPage' => 'assiduite',
            'demoMode' => $demoMode,
            'students' => $students,
            'stats' => $stats,
            'recentAbsences' => array_slice($absences, 0, 8),
            'recentRetards' => array_slice($retards, 0, 8),
        ], 'Assiduite');
    }
}
