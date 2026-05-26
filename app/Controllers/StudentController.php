<?php

namespace App\Controllers;

use App\Models\Student;
use App\Services\DemoStore;

class StudentController
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
        $students = $this->demoMode
            ? DemoStore::students()
            : Student::findAll($this->pdo);

        render('students/index', [
            'currentPage' => 'students',
            'demoMode' => $this->demoMode,
            'students' => $students,
        ], 'Étudiants');
    }

    public function create(): void
    {
        $this->form(null);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $student = $this->find($id);

        if (!$student) {
            flash('error', 'Étudiant introuvable.');
            redirect('students');
        }

        $this->form($student);
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
            DemoStore::deleteStudent($id);
        } else {
            Student::deleteById($this->pdo, $id);
        }

        flash('success', 'Étudiant supprimé.');
        redirect('students');
    }

    private function form(?array $student): void
    {
        $statuses = ['Applicant', 'Enrolled', 'Graduated', 'Suspended'];

        render('students/form', [
            'currentPage' => 'students',
            'demoMode' => $this->demoMode,
            'student' => $student,
            'statuses' => $statuses,
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? ($student ?? []),
        ], $student ? 'Modifier un étudiant' : 'Nouvel étudiant');

        unset($_SESSION['form_errors'], $_SESSION['form_old']);
    }

    private function save(array $data, int $id = 0): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $fields = [
            'first_name' => trim($data['first_name'] ?? ''),
            'last_name' => trim($data['last_name'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'file_number' => trim($data['file_number'] ?? ''),
            'enrollment_date' => trim($data['enrollment_date'] ?? date('Y-m-d')),
            'global_status' => trim($data['global_status'] ?? 'Applicant'),
        ];

        if ($this->demoMode) {
            $model = new Student(null);
            foreach ($fields as $key => $value) {
                match ($key) {
                    'first_name' => $model->setFirstName($value),
                    'last_name' => $model->setLastName($value),
                    'email' => $model->setEmail($value),
                    'file_number' => $model->setFileNumber($value),
                    'enrollment_date' => $model->setEnrollmentDate($value),
                    'global_status' => $model->setGlobalStatus($value),
                    default => null,
                };
            }

            if (!$model->validate()) {
                $_SESSION['form_errors'] = $model->getErrors();
                $_SESSION['form_old'] = array_merge($fields, $id ? ['id' => $id] : []);
                redirect($id ? 'student-edit' : 'student-create', $id ? ['id' => $id] : []);
            }

            DemoStore::saveStudent(array_merge($fields, $id ? ['id' => $id] : []));
        } else {
            if ($id > 0) {
                $ok = Student::updateById($this->pdo, $id, $fields);
            } else {
                $student = new Student($this->pdo, $fields['first_name'], $fields['last_name'], $fields['email'], $fields['file_number']);
                $student->setEnrollmentDate($fields['enrollment_date']);
                $student->setGlobalStatus($fields['global_status']);
                $ok = $student->save();
                if (!$ok) {
                    $_SESSION['form_errors'] = $student->getErrors();
                    $_SESSION['form_old'] = $fields;
                    redirect('student-create');
                }
            }

            if ($id > 0 && empty($ok)) {
                $_SESSION['form_errors'] = ['Erreur lors de la mise à jour.'];
                $_SESSION['form_old'] = array_merge($fields, ['id' => $id]);
                redirect('student-edit', ['id' => $id]);
            }
        }

        flash('success', $id ? 'Étudiant mis à jour.' : 'Étudiant créé.');
        redirect('students');
    }

    private function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if ($this->demoMode) {
            return DemoStore::findStudent($id);
        }

        return Student::findById($this->pdo, $id);
    }
}
