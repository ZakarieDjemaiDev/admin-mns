<?php

require_once __DIR__ . '/../config/app.php';

// Déclenche la session si elle n'est pas encore active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Page demandée via query string. Par défaut, on affiche le tableau de bord.
$page = $_GET['page'] ?? 'dashboard';

// Cartographie simple des routes publiques vers les méthodes du contrôleur.
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

// Si la page n'est pas gérée par l'application, on renvoie un 404.
if (!isset($routes[$page])) {
    http_response_code(404);
    echo 'Page introuvable';
    exit;
}

// Contrôleur principal. Toutes les pages sont servies par PageController.
$controller = new App\Controllers\PageController();
$method = $routes[$page];
$controller->$method();