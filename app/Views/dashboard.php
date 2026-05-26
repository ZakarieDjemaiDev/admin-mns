<div class="stats-grid">
    <div class="stat-card"><div><p class="stat-label">Etudiants</p><p class="stat-value"><?= (int)$stats['students'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Inscrits</p><p class="stat-value"><?= (int)$stats['enrolled'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Formations</p><p class="stat-value"><?= (int)$stats['trainings'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Absences (mois)</p><p class="stat-value"><?= (int)$stats['absences_month'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Retards (mois)</p><p class="stat-value"><?= (int)$stats['retards_month'] ?></p></div></div>
</div>
<div class="quick-grid">
    <div class="quick-card"><h3>Etudiants</h3><p>Liste et fiches maquette.</p><a href="<?= e(url('students')) ?>" class="btn btn-primary">Voir</a></div>
    <div class="quick-card"><h3>Formations</h3><p>Parcours de formation.</p><a href="<?= e(url('trainings')) ?>" class="btn btn-primary">Voir</a></div>
    <div class="quick-card"><h3>Assiduite</h3><p>Absences et retards.</p><a href="<?= e(url('assiduite')) ?>" class="btn btn-primary">Voir</a></div>
</div>