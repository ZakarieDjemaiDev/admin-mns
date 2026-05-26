<section class="panel">
    <div class="panel-header">
        <h2>Retards</h2>
        <a href="<?= e(url('retard-form')) ?>" class="btn btn-primary">+ Declarer</a>
    </div>
    <div class="panel-body">
        <table class="data-table">
            <thead><tr><th>Date</th><th>Etudiant</th><th>Minutes</th><th>Motif</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($retards as $r): ?>
                <tr>
                    <td><?= e(date('d/m/Y', strtotime($r['date']))) ?></td>
                    <td><?= e(studentLabel($students, (int) $r['student_id'])) ?></td>
                    <td><?= (int) $r['minutes_late'] ?> min</td>
                    <td><?= e($r['reason'] ?? '') ?></td>
                    <td><a href="<?= e(url('retard-form', ['id' => $r['id']])) ?>" class="btn btn-sm btn-secondary">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>