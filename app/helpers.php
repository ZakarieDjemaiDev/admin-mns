<?php

/**
 * Echappe une valeur pour l'affichage HTML.
 *
 * @param string|null $value Valeur à échapper.
 * @return string Valeur HTML-safe.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Construit une URL interne vers une page de l'application.
 *
 * @param string $page Nom logique de la page.
 * @param array $params Paramètres supplémentaires dans la query string.
 * @return string URL complète.
 */
function url(string $page, array $params = []): string
{
    $params = array_merge(['page' => $page], $params);
    return 'index.php?' . http_build_query($params);
}

/**
 * Renvoie le chemin public vers un asset statique.
 */
function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

/**
 * Redirige vers une autre page de l'application.
 */
function redirect(string $page, array $params = []): void
{
    header('Location: ' . url($page, $params));
    exit;
}

/**
 * Enregistre un message flash en session pour l'afficher après redirection.
 */
function flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Lit puis efface le message flash de la session.
 */
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

/**
 * Tente d'ouvrir une connexion PDO.
 *
 * Si la base de données n'est pas disponible, retourne null pour activer le mode demo.
 */
function tryDatabase(): ?\PDO
{
    try {
        return getDatabase();
    } catch (\Throwable $e) {
        return null;
    }
}

/**
 * Retourne la classe CSS du badge de statut en fonction du statut et de sa langue.
 */
function statusBadgeClass(string $status): string
{
    return match (strtolower($status)) {
        'enrolled', 'inscrit' => 'badge-enrolled',
        'graduated', 'diplome' => 'badge-graduated',
        'suspended', 'suspendu' => 'badge-suspended',
        default => 'badge-applicant',
    };
}

/**
 * Retourne le libellé d'un étudiant à partir de son identifiant.
 */
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

/**
 * Charge une vue, injecte les données et affiche le layout principal.
 */
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

/**
 * Affiche un bandeau de maquette lorsque la page ne supporte pas le back-end.
 */
function maquetteNotice(): void
{
    // Bandeau de maquette supprimé — ne rien afficher.
}