<?php

namespace App\Controllers;

use App\Models\Training;
use App\Services\DemoStore;

/**
 * TrainingController gère les actions CRUD des formations.
 *
 * En mode démo, les données sont conservées en session.
 * Sinon, les requêtes passent par le modèle Training et la base de données.
 */
class TrainingController
{
    private ?\PDO $pdo;
    private bool $demoMode;

    public function __construct()
    {
        // Essaie de récupérer une connexion PDO depuis la configuration.
        // Le back-end tente d'ouvrir la base de données ; sinon, on bascule en mode demo.
        $this->pdo = \tryDatabase();
        $this->demoMode = $this->pdo === null;
    }

    /**
     * Affiche la liste des formations enregistrées.
     */
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

    /**
     * Affiche le formulaire de création de formation.
     */
    public function create(): void
    {
        $this->form(null);
    }

    /**
     * Affiche le formulaire de modification d'une formation existante.
     */
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

    /**
     * Enregistre une nouvelle formation depuis $_POST.
     */
    public function store(): void
    {
        $this->save($_POST);
    }

    /**
     * Met à jour une formation existante depuis $_POST.
     */
    public function update(): void
    {
        $this->save($_POST, (int) ($_POST['id'] ?? 0));
    }

    /**
     * Supprime une formation.
     */
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

    /**
     * Affiche le formulaire de création/modification.
     */
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

    /**
     * Traite le formulaire soumis et enregistre les données.
     */
    private function save(array $data, int $id = 0): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Normalise les champs fournis par le formulaire.
        $fields = [
            'training_name' => trim($data['training_name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'duration_months' => (int) ($data['duration_months'] ?? 0),
            'start_date' => trim($data['start_date'] ?? ''),
            'total_places' => (int) ($data['total_places'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];

        if ($this->demoMode) {
            // Mode maquette : on utilise DemoStore en session.
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
            // Mode base de données : insertion / mise à jour via le modèle.
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

    /**
     * Retourne une formation par son ID en mode session ou base de données.
     */
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
