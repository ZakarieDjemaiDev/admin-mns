<?php
$flash = getFlash();
$pageTitle = $pageTitle ?? null;
$currentPage = $currentPage ?? '';
$content = $content ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(($pageTitle ?? '') ? $pageTitle . ' - ' . APP_NAME : APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h1><?= e(APP_NAME) ?></h1>
        </div>
        <nav class="sidebar-nav">
            <p class="nav-section">Menu</p>
            <a href="<?= e(url('dashboard')) ?>" class="nav-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
            <a href="<?= e(url('students')) ?>" class="nav-link <?= str_starts_with($currentPage ?? '', 'student') ? 'active' : '' ?>">Etudiants</a>
            <a href="<?= e(url('trainings')) ?>" class="nav-link <?= str_starts_with($currentPage ?? '', 'training') ? 'active' : '' ?>">Formations</a>
            <p class="nav-section">Assiduite</p>
            <a href="<?= e(url('assiduite')) ?>" class="nav-link <?= ($currentPage ?? '') === 'assiduite' ? 'active' : '' ?>">Tableau assiduite</a>
            <a href="<?= e(url('absences')) ?>" class="nav-link <?= str_starts_with($currentPage ?? '', 'absence') ? 'active' : '' ?>">Absences</a>
            <a href="<?= e(url('retards')) ?>" class="nav-link <?= str_starts_with($currentPage ?? '', 'retard') ? 'active' : '' ?>">Retards</a>
        </nav>
        <div class="sidebar-footer">v<?= e(APP_VERSION) ?></div>
    </aside>
    <div class="main-wrap">
        <header class="topbar">
            <h1 class="topbar-title"><?= e($pageTitle ?? 'Administration') ?></h1>
            <span class="topbar-meta"><?= date('d/m/Y') ?></span>
        </header>
        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type'] === 'error' ? 'error' : ($flash['type'] === 'warning' ? 'warning' : 'success')) ?>">
                    <?= e($flash['message']) ?>
                </div>
            <?php endif; ?>
            <?php maquetteNotice(); ?>
            <?= $content ?? '' ?>
        </main>
    </div>
</div>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>