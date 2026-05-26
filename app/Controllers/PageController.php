<?php

namespace App\Controllers;

use App\Data\MockData;

/**
 * PageController affiche les vues de la maquette front-end.
 *
 * Toutes les données sont chargées depuis MockData,
 * car il s'agit d'une interface de démonstration sans back-end complet.
 */
class PageController
{
    /**
     * Affiche le tableau de bord et prépare les statistiques.
     */
    public function dashboard(): void
    {
        $students = MockData::students();
        $trainings = MockData::trainings();
        $absences = MockData::absences();
        $retards = MockData::retards();
        $month = date('Y-m');

        $absencesMonth = array_filter($absences, fn($r) => str_starts_with($r['date'], $month));
        $retardsMonth = array_filter($retards, fn($r) => str_starts_with($r['date'], $month));

        render('dashboard', [
            'currentPage' => 'dashboard',
            'stats' => [
                'students' => count($students),
                'trainings' => count($trainings),
                'enrolled' => count(array_filter($students, fn($s) => $s['global_status'] === 'Enrolled')),
                'active_trainings' => count(array_filter($trainings, fn($t) => !empty($t['is_active']))),
                'absences_month' => count($absencesMonth),
                'retards_month' => count($retardsMonth),
            ],
            'recentStudents' => array_slice($students, 0, 5),
            'recentTrainings' => array_slice($trainings, 0, 3),
            'recentAbsences' => array_slice($absences, 0, 3),
            'recentRetards' => array_slice($retards, 0, 3),
            'students' => $students,
        ], 'Tableau de bord');
    }

    public function students(): void
    {
        render('students/index', [
            'currentPage' => 'students',
            'students' => MockData::students(),
        ], 'Etudiants');
    }

    public function studentForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $student = $id ? MockData::findStudent($id) : null;
        render('students/form', [
            'currentPage' => 'students',
            'student' => $student,
            'statuses' => ['Applicant', 'Enrolled', 'Graduated', 'Suspended'],
        ], $student ? 'Fiche etudiant (maquette)' : 'Nouvel etudiant (maquette)');
    }

    public function trainings(): void
    {
        render('trainings/index', [
            'currentPage' => 'trainings',
            'trainings' => MockData::trainings(),
        ], 'Formations');
    }

    public function trainingForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $training = $id ? MockData::findTraining($id) : null;
        render('trainings/form', [
            'currentPage' => 'trainings',
            'training' => $training,
        ], $training ? 'Fiche formation (maquette)' : 'Nouvelle formation (maquette)');
    }

    public function assiduite(): void
    {
        $absences = MockData::absences();
        $retards = MockData::retards();
        $students = MockData::students();
        $month = date('Y-m');
        $absencesMonth = array_filter($absences, fn($r) => str_starts_with($r['date'], $month));
        $retardsMonth = array_filter($retards, fn($r) => str_starts_with($r['date'], $month));
        $unjustified = array_filter($absencesMonth, fn($r) => empty($r['justified']));
        $totalMinutes = array_sum(array_column($retardsMonth, 'minutes_late'));

        render('assiduite/index', [
            'currentPage' => 'assiduite',
            'students' => $students,
            'absences' => $absences,
            'retards' => $retards,
            'stats' => [
                'absences_month' => count($absencesMonth),
                'retards_month' => count($retardsMonth),
                'unjustified_month' => count($unjustified),
                'minutes_month' => $totalMinutes,
            ],
        ], 'Assiduite');
    }

    public function absences(): void
    {
        render('absences/index', [
            'currentPage' => 'absences',
            'students' => MockData::students(),
            'absences' => MockData::absences(),
        ], 'Absences');
    }

    public function absenceForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        render('absences/form', [
            'currentPage' => 'absences',
            'students' => MockData::students(),
            'absence' => $id ? MockData::findAbsence($id) : null,
        ], 'Absence (maquette)');
    }

    public function retards(): void
    {
        render('retards/index', [
            'currentPage' => 'retards',
            'students' => MockData::students(),
            'retards' => MockData::retards(),
        ], 'Retards');
    }

    public function retardForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        render('retards/form', [
            'currentPage' => 'retards',
            'students' => MockData::students(),
            'retard' => $id ? MockData::findRetard($id) : null,
        ], 'Retard (maquette)');
    }
}