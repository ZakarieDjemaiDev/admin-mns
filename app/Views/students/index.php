<section class="panel">
    <div class="panel-header">
        <h2>Liste des etudiants</h2>
        <a href="<?= e(url('student-form')) ?>" class="btn btn-primary">+ Nouvel etudiant</a>
    </div>
    <div class="panel-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° dossier</th><th>Nom</th><th>Email</th><th>Inscription</th><th>Statut</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><strong><?= e($s['file_number']) ?></strong></td>
                    <td><?= e($s['first_name'] . ' ' . $s['last_name']) ?></td>
                    <td><?= e($s['email']) ?></td>
                    <td><?= e(date('d/m/Y', strtotime($s['enrollment_date']))) ?></td>
                    <td><span class="badge <?= statusBadgeClass($s['global_status']) ?>"><?= e($s['global_status']) ?></span></td>
                    <td><a href="<?= e(url('student-form', ['id' => $s['id']])) ?>" class="btn btn-sm btn-secondary">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>