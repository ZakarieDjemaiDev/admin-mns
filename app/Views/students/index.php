<section class="panel">
    <div class="panel-header">
        <h2>Liste des étudiants</h2>
        <a href="<?= e(url('student-create')) ?>" class="btn btn-primary">+ Nouvel étudiant</a>
    </div>
    <div class="panel-body">
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <p>Aucun étudiant enregistré.</p>
                <a href="<?= e(url('student-create')) ?>" class="btn btn-primary">Créer le premier étudiant</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° dossier</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><strong><?= e($student['file_number'] ?? '') ?></strong></td>
                            <td><?= e(trim(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''))) ?></td>
                            <td><?= e($student['email'] ?? '') ?></td>
                            <td><?= e(!empty($student['enrollment_date']) ? date('d/m/Y', strtotime($student['enrollment_date'])) : '—') ?></td>
                            <td>
                                <span class="badge <?= statusBadgeClass($student['global_status'] ?? '') ?>">
                                    <?= e($student['global_status'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= e(url('student-edit', ['id' => $student['id']])) ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form method="post" action="<?= e(url('student-delete')) ?>" data-confirm="Supprimer cet étudiant ?" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
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
