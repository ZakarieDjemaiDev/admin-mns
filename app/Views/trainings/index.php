<section class="panel">
    <div class="panel-header">
        <h2>Liste des formations</h2>
        <a href="<?= e(url('training-create')) ?>" class="btn btn-primary">+ Nouvelle formation</a>
    </div>
    <div class="panel-body">
        <?php if (empty($trainings)): ?>
            <div class="empty-state">
                <p>Aucune formation enregistrée.</p>
                <a href="<?= e(url('training-create')) ?>" class="btn btn-primary">Créer la première formation</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Formation</th>
                        <th>Durée</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Places</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainings as $training): ?>
                        <tr>
                            <td>
                                <strong><?= e($training['training_name'] ?? '') ?></strong>
                                <?php if (!empty($training['description'])): ?>
                                    <br><small style="color: var(--text-muted);"><?= e(mb_strimwidth($training['description'], 0, 60, '…')) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= (int) ($training['duration_months'] ?? 0) ?> mois</td>
                            <td><?= e(!empty($training['start_date']) ? date('d/m/Y', strtotime($training['start_date'])) : '—') ?></td>
                            <td><?= e(!empty($training['end_date']) ? date('d/m/Y', strtotime($training['end_date'])) : '—') ?></td>
                            <td><?= (int) ($training['total_places'] ?? 0) ?></td>
                            <td>
                                <span class="badge <?= !empty($training['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= !empty($training['is_active']) ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= e(url('training-edit', ['id' => $training['id']])) ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form method="post" action="<?= e(url('training-delete')) ?>" data-confirm="Supprimer cette formation ?" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= (int) $training['id'] ?>">
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
