<?php
$isEdit = !empty($training['id']);
$action = $isEdit ? url('training-update') : url('training-store');
$data = array_merge($training ?? [], $old ?? []);
?>

<section class="panel">
    <div class="panel-header">
        <h2><?= $isEdit ? 'Modifier la formation' : 'Créer une formation' ?></h2>
        <a href="<?= e(url('trainings')) ?>" class="btn btn-secondary">← Retour</a>
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
                <input type="hidden" name="id" value="<?= (int) $training['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="training_name">Nom de la formation <span class="required">*</span></label>
                    <input type="text" id="training_name" name="training_name" class="form-control" required
                           value="<?= e($data['training_name'] ?? '') ?>">
                </div>
                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"><?= e($data['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="duration_months">Durée (mois) <span class="required">*</span></label>
                    <input type="number" id="duration_months" name="duration_months" class="form-control" min="1" required
                           value="<?= (int) ($data['duration_months'] ?? 12) ?>">
                </div>
                <div class="form-group">
                    <label for="total_places">Nombre de places <span class="required">*</span></label>
                    <input type="number" id="total_places" name="total_places" class="form-control" min="1" required
                           value="<?= (int) ($data['total_places'] ?? 20) ?>">
                </div>
                <div class="form-group">
                    <label for="start_date">Date de début</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                           value="<?= e($data['start_date'] ?? '') ?>">
                    <span class="form-hint">La date de fin est calculée automatiquement.</span>
                </div>
                <div class="form-group">
                    <label>Fin estimée</label>
                    <p id="end_date_preview" style="margin: 0.6rem 0; font-weight: 600; color: var(--primary);">
                        <?= e($data['end_date'] ?? '—') ?>
                    </p>
                </div>
                <div class="form-group full-width">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= !isset($data['is_active']) || !empty($data['is_active']) ? 'checked' : '' ?>>
                        Formation active
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer la formation' ?></button>
                <a href="<?= e(url('trainings')) ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
