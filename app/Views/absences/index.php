<section class="panel">
    <div class="panel-header">
        <h2>Liste des absences</h2>
        <a href="<?= e(url('absence-create')) ?>" class="btn btn-primary">+ Nouvelle absence</a>
    </div>
    <div class="panel-body">
        <?php if (empty($absences)): ?>
            <div class="empty-state">
                <p>Aucune absence enregistree.</p>
                <a href="<?= e(url('absence-create')) ?>" class="btn btn-primary">Enregistrer une absence</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Etudiant</th>
                        <th>Motif</th>
                        <th>Justifiee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($absences as $absence): ?>
                        <tr>
                            <td><?= e(!empty($absence['date']) ? date('d/m/Y', strtotime($absence['date'])) : '') ?></td>
                            <td><?= e(studentLabel($students, (int) ($absence['student_id'] ?? 0))) ?></td>
                            <td><?= e($absence['reason'] ?? '') ?></td>
                            <td>
                                <?php if (!empty($absence['justified'])): ?>
                                    <span class="badge badge-enrolled">Oui</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Non</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= e(url('absence-edit', ['id' => $absence['id']])) ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form method="post" action="<?= e(url('absence-delete')) ?>" data-confirm="Supprimer cette absence ?" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= (int) $absence['id'] ?>">
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
