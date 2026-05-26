<div class="stats-grid">
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
    <div class="stat-card">
        <div class="stat-icon red">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="stat-label">Absences non justifiees</p>
            <p class="stat-value"><?= (int) $stats['unjustified_absences_month'] ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <p class="stat-label">Minutes de retard</p>
            <p class="stat-value"><?= (int) $stats['minutes_late_month'] ?></p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem; margin-top: 1.75rem;">
    <section class="panel">
        <div class="panel-header">
            <h2>Absences recentes</h2>
            <a href="<?= e(url('absence-create')) ?>" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        <div class="panel-body">
            <?php if (empty($recentAbsences)): ?>
                <div class="empty-state"><p>Aucune absence enregistree.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Etudiant</th>
                            <th>Motif</th>
                            <th>Justifiee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAbsences as $a): ?>
                            <tr>
                                <td><?= e(!empty($a['date']) ? date('d/m/Y', strtotime($a['date'])) : '') ?></td>
                                <td><?= e(studentLabel($students, (int) ($a['student_id'] ?? 0))) ?></td>
                                <td><?= e($a['reason'] ?? '') ?></td>
                                <td>
                                    <?php if (!empty($a['justified'])): ?>
                                        <span class="badge badge-enrolled">Oui</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Non</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>Retards recents</h2>
            <a href="<?= e(url('retard-create')) ?>" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        <div class="panel-body">
            <?php if (empty($recentRetards)): ?>
                <div class="empty-state"><p>Aucun retard enregistre.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Etudiant</th>
                            <th>Minutes</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentRetards as $r): ?>
                            <tr>
                                <td><?= e(!empty($r['date']) ? date('d/m/Y', strtotime($r['date'])) : '') ?></td>
                                <td><?= e(studentLabel($students, (int) ($r['student_id'] ?? 0))) ?></td>
                                <td><?= (int) ($r['minutes_late'] ?? 0) ?></td>
                                <td><?= e($r['reason'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</div>

<div class="quick-grid" style="margin-top: 1.75rem;">
    <div class="quick-card">
        <h3>Gerer les absences</h3>
        <p>Consulter, ajouter ou modifier les absences des etudiants.</p>
        <a href="<?= e(url('absences')) ?>" class="btn btn-primary">Voir les absences</a>
    </div>
    <div class="quick-card">
        <h3>Gerer les retards</h3>
        <p>Suivre les retards et le temps perdu en cours.</p>
        <a href="<?= e(url('retards')) ?>" class="btn btn-primary">Voir les retards</a>
    </div>
</div>
