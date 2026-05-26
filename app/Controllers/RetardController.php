<?php

namespace App\Controllers;

use App\Models\Retard;
use App\Models\Student;
use App\Services\DemoStore;

class RetardController
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
        $retards = $this->demoMode
            ? DemoStore::retards()
            : Retard::findAll($this->pdo);

        $students = $this->demoMode
            ? DemoStore::students()
            : Student::findAll($this->pdo);

        usort($retards, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));

        render('retards/index', [
            'currentPage' => 'retards',
            'demoMode' => $this->demoMode,
            'retards' => $retards,
            'students' => $students,
        ], 'Retards');
    }

    public function create(): void
    {
        $this->form(null);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $retard = $this->find($id);

        if (!$retard) {
            flash('error', 'Retard introuvable.');
            redirect('retards');
        }

        $this->form($retard);
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
            DemoStore::deleteRetard($id);
        } else {
            Retard::deleteById($this->pdo, $id);
        }

        flash('success', 'Retard supprime.');
        redirect('retards');
    }

    private function form(?array $retard): void
    {
        $students = $this->demoMode
            ? DemoStore::students()
            : Student::findAll($this->pdo);

        render('retards/form', [
            'currentPage' => 'retards',
            'demoMode' => $this->demoMode,
            'retard' => $retard,
            'students' => $students,
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? ($retard ?? []),
        ], $retard ? 'Modifier un retard' : 'Nouveau retard');

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
            'minutes_late' => max(0, (int) ($data['minutes_late'] ?? 0)),
            'reason' => trim($data['reason'] ?? ''),
        ];

        $errors = [];
        if ($fields['student_id'] <= 0) {
            $errors[] = 'Veuillez selectionner un etudiant.';
        }
        if ($fields['date'] === '') {
            $errors[] = 'La date est obligatoire.';
        }
        if ($fields['minutes_late'] <= 0) {
            $errors[] = 'Indiquez le nombre de minutes de retard.';
        }

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
            redirect($id ? 'retard-edit' : 'retard-create', $id ? ['id' => $id] : []);
        }

        if ($this->demoMode) {
            DemoStore::saveRetard(array_merge($fields, $id ? ['id' => $id] : []));
        } else {
            if ($id > 0) {
                $ok = Retard::updateById($this->pdo, $id, $fields);
            } else {
                $ok = Retard::create($this->pdo, $fields);
            }

            if (empty($ok)) {
                $_SESSION['form_errors'] = ['Erreur lors de l\'enregistrement.'];
                $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
                redirect($id ? 'retard-edit' : 'retard-create', $id ? ['id' => $id] : []);
            }
        }

        flash('success', $id ? 'Retard mis a jour.' : 'Retard enregistre.');
        redirect('retards');
    }

    private function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if ($this->demoMode) {
            return DemoStore::findRetard($id);
        }

        return Retard::findById($this->pdo, $id);
    }
}
