<?php

namespace App\Controllers;

use App\Models\Absence;
use App\Models\Student;
use App\Services\DemoStore;

class AbsenceController
{
    private ?\PDO $pdo;
    private bool $demoMode;

    public function __construct()
    {
        $this->pdo = tryDatabase();
        $this->demoMode = $this->pdo === null;
    }

    public function index(): void
    {
        $absences = $this->demoMode
            ? DemoStore::absences()
            : Absence::findAll($this->pdo);

        $students = $this->demoMode
            ? DemoStore::students()
            : Student::findAll($this->pdo);

        usort($absences, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));

        render('absences/index', [
            'currentPage' => 'absences',
            'demoMode' => $this->demoMode,
            'absences' => $absences,
            'students' => $students,
        ], 'Absences');
    }

    public function create(): void
    {
        $this->form(null);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $absence = $this->find($id);

        if (!$absence) {
            flash('error', 'Absence introuvable.');
            redirect('absences');
        }

        $this->form($absence);
    }

    public function store(): void
    {
        $this->save($_POST);
    }

    public function update(): void
    {
        $this->save($_POST, (int) ($_POST['id'] ?? 0));
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($this->demoMode) {
            DemoStore::deleteAbsence($id);
        } else {
            Absence::deleteById($this->pdo, $id);
        }

        flash('success', 'Absence supprimee.');
        redirect('absences');
    }

    private function form(?array $absence): void
    {
        $students = $this->demoMode
            ? DemoStore::students()
            : Student::findAll($this->pdo);

        render('absences/form', [
            'currentPage' => 'absences',
            'demoMode' => $this->demoMode,
            'absence' => $absence,
            'students' => $students,
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? ($absence ?? []),
        ], $absence ? 'Modifier une absence' : 'Nouvelle absence');

        unset($_SESSION['form_errors'], $_SESSION['form_old']);
    }

    private function save(array $data, int $id = 0): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $fields = [
            'student_id' => (int) ($data['student_id'] ?? 0),
            'date' => trim($data['date'] ?? ''),
            'reason' => trim($data['reason'] ?? ''),
            'justified' => isset($data['justified']) ? 1 : 0,
        ];

        $errors = [];
        if ($fields['student_id'] <= 0) {
            $errors[] = 'Veuillez selectionner un etudiant.';
        }
        if ($fields['date'] === '') {
            $errors[] = 'La date est obligatoire.';
        }

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
            redirect($id ? 'absence-edit' : 'absence-create', $id ? ['id' => $id] : []);
        }

        if ($this->demoMode) {
            DemoStore::saveAbsence(array_merge($fields, $id ? ['id' => $id] : []));
        } else {
            if ($id > 0) {
                $ok = Absence::updateById($this->pdo, $id, $fields);
            } else {
                $ok = Absence::create($this->pdo, $fields);
            }

            if (empty($ok)) {
                $_SESSION['form_errors'] = ['Erreur lors de l\'enregistrement.'];
                $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
                redirect($id ? 'absence-edit' : 'absence-create', $id ? ['id' => $id] : []);
            }
        }

        flash('success', $id ? 'Absence mise a jour.' : 'Absence enregistree.');
        redirect('absences');
    }

    private function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if ($this->demoMode) {
            return DemoStore::findAbsence($id);
        }

        return Absence::findById($this->pdo, $id);
    }
}
