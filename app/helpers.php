<?php

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $page, array $params = []): string
{
    $params = array_merge(['page' => $page], $params);
    return 'index.php?' . http_build_query($params);
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function redirect(string $page, array $params = []): void
{
    header('Location: ' . url($page, $params));
    exit;
}

function flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function statusBadgeClass(string $status): string
{
    return match (strtolower($status)) {
        'enrolled', 'inscrit' => 'badge-enrolled',
        'graduated', 'diplome' => 'badge-graduated',
        'suspended', 'suspendu' => 'badge-suspended',
        default => 'badge-applicant',
    };
}

function studentLabel(array $students, int $studentId): string
{
    foreach ($students as $student) {
        if ((int) ($student['id'] ?? 0) === $studentId) {
            $name = trim(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''));
            $file = $student['file_number'] ?? '';
            return $file ? "$name ($file)" : $name;
        }
    }
    return 'Etudiant #' . $studentId;
}

function render(string $view, array $data = [], string $title = ''): void
{
    extract($data, EXTR_SKIP);
    $pageTitle = $title;
    $currentPage = $data['currentPage'] ?? '';
    $frontOnly = true;

    ob_start();
    $viewFile = ROOT_PATH . '/app/Views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        ob_end_clean();
        http_response_code(404);
        echo 'Vue introuvable : ' . e($view);
        return;
    }
    require $viewFile;
    $content = ob_get_clean();
    require ROOT_PATH . '/app/Views/layout.php';
}

function maquetteNotice(): void
{
    echo '<div class="maquette-banner">Maquette <strong>front uniquement</strong> : donnees fictives, formulaires non enregistres (back-end / PDO a venir).</div>';
}