<?php

namespace App\Controllers;

use App\Models\Training;
use App\Services\DemoStore;

class TrainingController
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
        $trainings = $this->demoMode
            ? DemoStore::trainings()
            : Training::findAll($this->pdo);

        render('trainings/index', [
            'currentPage' => 'trainings',
            'demoMode' => $this->demoMode,
            'trainings' => $trainings,
        ], 'Formations');
    }

    public function create(): void
    {
        $this->form(null);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $training = $this->find($id);

        if (!$training) {
            flash('error', 'Formation introuvable.');
            redirect('trainings');
        }

        $this->form($training);
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
            DemoStore::deleteTraining($id);
        } else {
            Training::deleteById($this->pdo, $id);
        }

        flash('success', 'Formation supprimée.');
        redirect('trainings');
    }

    private function form(?array $training): void
    {
        render('trainings/form', [
            'currentPage' => 'trainings',
            'demoMode' => $this->demoMode,
            'training' => $training,
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? ($training ?? []),
        ], $training ? 'Modifier une formation' : 'Nouvelle formation');

        unset($_SESSION['form_errors'], $_SESSION['form_old']);
    }

    private function save(array $data, int $id = 0): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $fields = [
            'training_name' => trim($data['training_name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'duration_months' => (int) ($data['duration_months'] ?? 0),
            'start_date' => trim($data['start_date'] ?? ''),
            'total_places' => (int) ($data['total_places'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];

        if ($this->demoMode) {
            $model = new Training(null, $fields['training_name'], $fields['duration_months']);
            $model->setDescription($fields['description']);
            $model->setStartDate($fields['start_date']);
            $model->setTotalPlaces($fields['total_places']);

            if (!$model->validate()) {
                $_SESSION['form_errors'] = $model->getErrors();
                $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
                redirect($id ? 'training-edit' : 'training-create', $id ? ['id' => $id] : []);
            }

            $row = array_merge($fields, [
                'end_date' => $model->calculateEndDate() ?: ($data['end_date'] ?? ''),
            ]);

            DemoStore::saveTraining(array_merge($row, $id ? ['id' => $id] : []));
        } else {
            if ($id > 0) {
                $ok = Training::updateById($this->pdo, $id, $fields);
            } else {
                $training = new Training($this->pdo, $fields['training_name'], $fields['duration_months']);
                $training->setDescription($fields['description']);
                $training->setStartDate($fields['start_date']);
                $training->setTotalPlaces($fields['total_places']);
                $ok = $training->save();
                if (!$ok) {
                    $_SESSION['form_errors'] = $training->getErrors();
                    $_SESSION['form_old'] = $fields;
                    redirect('training-create');
                }
            }

            if ($id > 0 && empty($ok)) {
                $_SESSION['form_errors'] = ['Erreur lors de la mise à jour.'];
                $_SESSION['form_old'] = array_merge($fields, ['id' => $id]);
                redirect('training-edit', ['id' => $id]);
            }
        }

        flash('success', $id ? 'Formation mise à jour.' : 'Formation créée.');
        redirect('trainings');
    }

    private function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if ($this->demoMode) {
            return DemoStore::findTraining($id);
        }

        return Training::findById($this->pdo, $id);
    }
}
