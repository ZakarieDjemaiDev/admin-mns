<?php

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'dashboard' => ['App\\Controllers\\DashboardController', 'index', 'GET'],
    'students' => ['App\\Controllers\\StudentController', 'index', 'GET'],
    'student-create' => ['App\\Controllers\\StudentController', 'create', 'GET'],
    'student-edit' => ['App\\Controllers\\StudentController', 'edit', 'GET'],
    'student-store' => ['App\\Controllers\\StudentController', 'store', 'POST'],
    'student-update' => ['App\\Controllers\\StudentController', 'update', 'POST'],
    'student-delete' => ['App\\Controllers\\StudentController', 'delete', 'POST'],
    'trainings' => ['App\\Controllers\\TrainingController', 'index', 'GET'],
    'training-create' => ['App\\Controllers\\TrainingController', 'create', 'GET'],
    'training-edit' => ['App\\Controllers\\TrainingController', 'edit', 'GET'],
    'training-store' => ['App\\Controllers\\TrainingController', 'store', 'POST'],
    'training-update' => ['App\\Controllers\\TrainingController', 'update', 'POST'],
    'training-delete' => ['App\\Controllers\\TrainingController', 'delete', 'POST'],
    'assiduite' => ['App\\Controllers\\AssiduiteController', 'index', 'GET'],
    'absences' => ['App\\Controllers\\AbsenceController', 'index', 'GET'],
    'absence-create' => ['App\\Controllers\\AbsenceController', 'create', 'GET'],
    'absence-edit' => ['App\\Controllers\\AbsenceController', 'edit', 'GET'],
    'absence-store' => ['App\\Controllers\\AbsenceController', 'store', 'POST'],
    'absence-update' => ['App\\Controllers\\AbsenceController', 'update', 'POST'],
    'absence-delete' => ['App\\Controllers\\AbsenceController', 'delete', 'POST'],
    'retards' => ['App\\Controllers\\RetardController', 'index', 'GET'],
    'retard-create' => ['App\\Controllers\\RetardController', 'create', 'GET'],
    'retard-edit' => ['App\\Controllers\\RetardController', 'edit', 'GET'],
    'retard-store' => ['App\\Controllers\\RetardController', 'store', 'POST'],
    'retard-update' => ['App\\Controllers\\RetardController', 'update', 'POST'],
    'retard-delete' => ['App\\Controllers\\RetardController', 'delete', 'POST'],
];

if (!isset($routes[$page])) {
    http_response_code(404);
    echo 'Page introuvable';
    exit;
}

[$class, $action, $expectedMethod] = $routes[$page];

if ($method !== $expectedMethod) {
    http_response_code(405);
    echo 'Methode non autorisee';
    exit;
}

$controller = new $class();
$controller->$action();
