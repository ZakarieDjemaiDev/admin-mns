<?php $data = $student ?? []; $readonly = true; ?>
<section class="panel">
    <div class="panel-header">
        <h2><?= !empty($student) ? 'Fiche etudiant' : 'Nouvel etudiant' ?></h2>
        <a href="<?= e(url('students')) ?>" class="btn btn-secondary">Retour</a>
    </div>
    <div class="panel-body padded">
        <form>
            <div class="form-grid">
                <div class="form-group"><label>Prenom</label><input class="form-control" value="<?= e($data['first_name'] ?? '') ?>" readonly></div>
                <div class="form-group"><label>Nom</label><input class="form-control" value="<?= e($data['last_name'] ?? '') ?>" readonly></div>
                <div class="form-group"><label>Email</label><input class="form-control" value="<?= e($data['email'] ?? '') ?>" readonly></div>
                <div class="form-group"><label>N° dossier</label><input class="form-control" value="<?= e($data['file_number'] ?? 'STU-2026-XXX') ?>" readonly></div>
                <div class="form-group"><label>Date inscription</label><input type="date" class="form-control" value="<?= e($data['enrollment_date'] ?? date('Y-m-d')) ?>" readonly></div>
                <div class="form-group"><label>Statut</label>
                    <select class="form-control" disabled>
                        <?php foreach ($statuses as $st): ?>
                        <option <?= ($data['global_status'] ?? '') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" disabled title="Back-end a implementer">Enregistrer (a venir)</button>
            </div>
        </form>
    </div>
</section>