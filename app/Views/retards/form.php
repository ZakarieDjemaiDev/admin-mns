<?php
$isEdit = !empty($retard['id']);
$action = $isEdit ? url('retard-update') : url('retard-store');
$data = array_merge($retard ?? [], $old ?? []);
?>

<section class="panel">
    <div class="panel-header">
        <h2><?= $isEdit ? 'Modifier le retard' : 'Enregistrer un retard' ?></h2>
        <a href="<?= e(url('retards')) ?>" class="btn btn-secondary">Retour</a>
    </div>
    <div class="panel-body padded">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Veuillez corriger les erreurs :</strong>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e($action) ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $retard['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="student_id">Etudiant <span class="required">*</span></label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= (int) $student['id'] ?>" <?= (int) ($data['student_id'] ?? 0) === (int) $student['id'] ? 'selected' : '' ?>>
                                <?= e(studentLabel($students, (int) $student['id'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date <span class="required">*</span></label>
                    <input type="date" id="date" name="date" class="form-control" required
                           value="<?= e($data['date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label for="minutes_late">Minutes de retard <span class="required">*</span></label>
                    <input type="number" id="minutes_late" name="minutes_late" class="form-control" min="1" required
                           value="<?= (int) ($data['minutes_late'] ?? 0) ?>">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="reason">Motif</label>
                    <input type="text" id="reason" name="reason" class="form-control"
                           value="<?= e($data['reason'] ?? '') ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Creer' ?></button>
                <a href="<?= e(url('retards')) ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
