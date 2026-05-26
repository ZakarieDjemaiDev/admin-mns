<section class="panel">
    <div class="panel-header">
        <h2>Liste des retards</h2>
        <a href="<?= e(url('retard-create')) ?>" class="btn btn-primary">+ Nouveau retard</a>
    </div>
    <div class="panel-body">
        <?php if (empty($retards)): ?>
            <div class="empty-state">
                <p>Aucun retard enregistre.</p>
                <a href="<?= e(url('retard-create')) ?>" class="btn btn-primary">Enregistrer un retard</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Etudiant</th>
                        <th>Minutes</th>
                        <th>Motif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($retards as $retard): ?>
                        <tr>
                            <td><?= e(!empty($retard['date']) ? date('d/m/Y', strtotime($retard['date'])) : '') ?></td>
                            <td><?= e(studentLabel($students, (int) ($retard['student_id'] ?? 0))) ?></td>
                            <td><?= (int) ($retard['minutes_late'] ?? 0) ?></td>
                            <td><?= e($retard['reason'] ?? '') ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= e(url('retard-edit', ['id' => $retard['id']])) ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form method="post" action="<?= e(url('retard-delete')) ?>" data-confirm="Supprimer ce retard ?" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= (int) $retard['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
