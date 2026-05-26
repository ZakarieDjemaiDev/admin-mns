<section class="panel">
    <div class="panel-header">
        <h2>Liste des formations</h2>
        <a href="<?= e(url('training-form')) ?>" class="btn btn-primary">+ Nouvelle formation</a>
    </div>
    <div class="panel-body">
        <table class="data-table">
            <thead><tr><th>Formation</th><th>Duree</th><th>Places</th><th>Statut</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($trainings as $t): ?>
                <tr>
                    <td><strong><?= e($t['training_name']) ?></strong></td>
                    <td><?= (int) $t['duration_months'] ?> mois</td>
                    <td><?= (int) $t['total_places'] ?></td>
                    <td><span class="badge <?= !empty($t['is_active']) ? 'badge-active' : 'badge-inactive' ?>"><?= !empty($t['is_active']) ? 'Active' : 'Inactive' ?></span></td>
                    <td><a href="<?= e(url('training-form', ['id' => $t['id']])) ?>" class="btn btn-sm btn-secondary">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>