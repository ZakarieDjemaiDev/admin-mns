<?php

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';

$routes = [
    'dashboard' => 'dashboard',
    'students' => 'students',
    'student-form' => 'studentForm',
    'trainings' => 'trainings',
    'training-form' => 'trainingForm',
    'assiduite' => 'assiduite',
    'absences' => 'absences',
    'absence-form' => 'absenceForm',
    'retards' => 'retards',
    'retard-form' => 'retardForm',
];

if (!isset($routes[$page])) {
    http_response_code(404);
    echo 'Page introuvable';
    exit;
}

$controller = new App\Controllers\PageController();
$method = $routes[$page];
$controller->$method();