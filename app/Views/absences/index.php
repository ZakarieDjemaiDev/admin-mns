<section class="panel">
    <div class="panel-header">
        <h2>Absences</h2>
        <a href="<?= e(url('absence-form')) ?>" class="btn btn-primary">+ Declarer</a>
    </div>
    <div class="panel-body">
        <table class="data-table">
            <thead><tr><th>Date</th><th>Etudiant</th><th>Motif</th><th>Justifiee</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($absences as $a): ?>
                <tr>
                    <td><?= e(date('d/m/Y', strtotime($a['date']))) ?></td>
                    <td><?= e(studentLabel($students, (int) $a['student_id'])) ?></td>
                    <td><?= e($a['reason'] ?? '') ?></td>
                    <td><?= !empty($a['justified']) ? 'Oui' : 'Non' ?></td>
                    <td><a href="<?= e(url('absence-form', ['id' => $a['id']])) ?>" class="btn btn-sm btn-secondary">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>