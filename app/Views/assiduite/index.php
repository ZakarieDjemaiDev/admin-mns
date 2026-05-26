<div class="stats-grid">
    <div class="stat-card"><div><p class="stat-label">Absences ce mois</p><p class="stat-value"><?= (int)$stats['absences_month'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Retards ce mois</p><p class="stat-value"><?= (int)$stats['retards_month'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Non justifiees</p><p class="stat-value"><?= (int)$stats['unjustified_month'] ?></p></div></div>
    <div class="stat-card"><div><p class="stat-label">Minutes de retard</p><p class="stat-value"><?= (int)$stats['minutes_month'] ?></p></div></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <section class="panel">
        <div class="panel-header"><h2>Absences</h2><a href="<?= e(url('absences')) ?>" class="btn btn-sm btn-primary">Liste</a></div>
        <div class="panel-body"><table class="data-table"><tbody>
            <?php foreach (array_slice($absences,0,5) as $a): ?>
            <tr><td><?= e(date('d/m/Y',strtotime($a['date']))) ?></td><td><?= e(studentLabel($students,(int)$a['student_id'])) ?></td></tr>
            <?php endforeach; ?>
        </tbody></table></div>
    </section>
    <section class="panel">
        <div class="panel-header"><h2>Retards</h2><a href="<?= e(url('retards')) ?>" class="btn btn-sm btn-primary">Liste</a></div>
        <div class="panel-body"><table class="data-table"><tbody>
            <?php foreach (array_slice($retards,0,5) as $r): ?>
            <tr><td><?= e(date('d/m/Y',strtotime($r['date']))) ?></td><td><?= e(studentLabel($students,(int)$r['student_id'])) ?> — <?= (int)$r['minutes_late'] ?> min</td></tr>
            <?php endforeach; ?>
        </tbody></table></div>
    </section>
</div>