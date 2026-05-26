<?php $data = $training ?? []; ?>
<section class="panel">
    <div class="panel-header">
        <h2>Fiche formation</h2>
        <a href="<?= e(url('trainings')) ?>" class="btn btn-secondary">Retour</a>
    </div>
    <div class="panel-body padded">
        <form>
            <div class="form-grid">
                <div class="form-group full-width"><label>Nom</label><input class="form-control" value="<?= e($data['training_name'] ?? '') ?>" readonly></div>
                <div class="form-group full-width"><label>Description</label><textarea class="form-control" readonly><?= e($data['description'] ?? '') ?></textarea></div>
                <div class="form-group"><label>Duree (mois)</label><input type="number" class="form-control" value="<?= (int) ($data['duration_months'] ?? 12) ?>" readonly></div>
                <div class="form-group"><label>Places</label><input type="number" class="form-control" value="<?= (int) ($data['total_places'] ?? 20) ?>" readonly></div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" disabled>Enregistrer (a venir)</button>
            </div>
        </form>
    </div>
</section>