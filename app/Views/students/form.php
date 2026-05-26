<?php
$isEdit = !empty($student['id']);
$action = $isEdit ? url('student-update') : url('student-store');
$data = array_merge($student ?? [], $old ?? []);
?>

<section class="panel">
    <div class="panel-header">
        <h2><?= $isEdit ? 'Modifier l\'étudiant' : 'Créer un étudiant' ?></h2>
        <a href="<?= e(url('students')) ?>" class="btn btn-secondary">← Retour</a>
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
                <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Prénom <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required
                           value="<?= e($data['first_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Nom <span class="required">*</span></label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required
                           value="<?= e($data['last_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?= e($data['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="file_number">N° de dossier <span class="required">*</span></label>
                    <input type="text" id="file_number" name="file_number" class="form-control" required
                           placeholder="STU-2025-001"
                           value="<?= e($data['file_number'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="enrollment_date">Date d'inscription <span class="required">*</span></label>
                    <input type="date" id="enrollment_date" name="enrollment_date" class="form-control" required
                           value="<?= e($data['enrollment_date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label for="global_status">Statut</label>
                    <select id="global_status" name="global_status" class="form-control">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>" <?= ($data['global_status'] ?? '') === $status ? 'selected' : '' ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer l\'étudiant' ?></button>
                <a href="<?= e(url('students')) ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
