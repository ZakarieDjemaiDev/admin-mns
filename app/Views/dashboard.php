<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon indigo">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="stat-label">Etudiants</p>
            <p class="stat-value"><?= (int) $stats['students'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="stat-label">Inscrits</p>
            <p class="stat-value"><?= (int) $stats['enrolled'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="stat-label">Formations</p>
            <p class="stat-value"><?= (int) $stats['trainings'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <p class="stat-label">Formations actives</p>
            <p class="stat-value"><?= (int) $stats['active_trainings'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </div>
        <div>
            <p class="stat-label">Absences (mois)</p>
            <p class="stat-value"><?= (int) $stats['absences_month'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="stat-label">Retards (mois)</p>
            <p class="stat-value"><?= (int) $stats['retards_month'] ?></p>
        </div>
    </div>
</div>

<div class="quick-grid">
    <div class="quick-card">
        <h3>Gerer les etudiants</h3>
        <p>Consulter, ajouter ou modifier les dossiers etudiants.</p>
        <a href="<?= e(url('students')) ?>" class="btn btn-primary">Voir les etudiants</a>
    </div>
    <div class="quick-card">
        <h3>Gerer les formations</h3>
        <p>Creer des parcours, definir les places et les dates.</p>
        <a href="<?= e(url('trainings')) ?>" class="btn btn-primary">Voir les formations</a>
    </div>
    <div class="quick-card">
        <h3>Assiduite</h3>
        <p>Tableau de bord des absences et retards du mois.</p>
        <a href="<?= e(url('assiduite')) ?>" class="btn btn-primary">Tableau assiduite</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem; margin-top: 1.75rem;">
    <section class="panel">
        <div class="panel-header">
            <h2>Derniers etudiants</h2>
            <a href="<?= e(url('student-create')) ?>" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        <div class="panel-body">
            <?php if (empty($recentStudents)): ?>
                <div class="empty-state"><p>Aucun etudiant pour le moment.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Dossier</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentStudents as $s): ?>
                            <tr>
                                <td><?= e(trim(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? ''))) ?></td>
                                <td><?= e($s['file_number'] ?? '') ?></td>
                                <td><span class="badge <?= statusBadgeClass($s['global_status'] ?? '') ?>"><?= e($s['global_status'] ?? '') ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>Formations recentes</h2>
            <a href="<?= e(url('training-create')) ?>" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        <div class="panel-body">
            <?php if (empty($recentTrainings)): ?>
                <div class="empty-state"><p>Aucune formation pour le moment.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Formation</th>
                            <th>Duree</th>
                            <th>Places</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTrainings as $t): ?>
                            <tr>
                                <td><?= e($t['training_name'] ?? '') ?></td>
                                <td><?= (int) ($t['duration_months'] ?? 0) ?> mois</td>
                                <td><?= (int) ($t['total_places'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</div>

<section class="panel" style="margin-top: 1.75rem;">
    <div class="panel-header">
        <h2>Assiduite recente</h2>
        <a href="<?= e(url('assiduite')) ?>" class="btn btn-sm btn-secondary">Voir tout</a>
    </div>
    <div class="panel-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div>
            <h3 style="margin: 0 0 0.75rem; font-size: 0.95rem;">Absences</h3>
            <?php if (empty($recentAbsences)): ?>
                <p class="text-muted" style="margin:0;">Aucune absence ce mois.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Etudiant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAbsences as $a): ?>
                            <tr>
                                <td><?= e(!empty($a['date']) ? date('d/m/Y', strtotime($a['date'])) : '') ?></td>
                                <td><?= e(studentLabel($students, (int) ($a['student_id'] ?? 0))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div>
            <h3 style="margin: 0 0 0.75rem; font-size: 0.95rem;">Retards</h3>
            <?php if (empty($recentRetards)): ?>
                <p class="text-muted" style="margin:0;">Aucun retard ce mois.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Etudiant</th>
                            <th>Min.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentRetards as $r): ?>
                            <tr>
                                <td><?= e(!empty($r['date']) ? date('d/m/Y', strtotime($r['date'])) : '') ?></td>
                                <td><?= e(studentLabel($students, (int) ($r['student_id'] ?? 0))) ?></td>
                                <td><?= (int) ($r['minutes_late'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</section>
