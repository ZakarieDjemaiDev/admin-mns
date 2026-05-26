<?php $data = $retard ?? []; ?>
<section class="panel">
    <div class="panel-header"><h2>Retard</h2><a href="<?= e(url('retards')) ?>" class="btn btn-secondary">Retour</a></div>
    <div class="panel-body padded">
        <form>
            <div class="form-grid">
                <div class="form-group"><label>Etudiant</label>
                    <select class="form-control" disabled>
                        <?php foreach ($students as $s): ?>
                        <option <?= ((int)($data['student_id']??0))===(int)$s['id']?'selected':'' ?>><?= e(studentLabel($students, (int)$s['id'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Date</label><input type="date" class="form-control" value="<?= e($data['date'] ?? date('Y-m-d')) ?>" readonly></div>
                <div class="form-group"><label>Minutes</label><input type="number" class="form-control" value="<?= (int)($data['minutes_late']??0) ?>" readonly></div>
                <div class="form-group full-width"><label>Motif</label><input class="form-control" value="<?= e($data['reason'] ?? '') ?>" readonly></div>
            </div>
            <div class="form-actions"><button type="button" class="btn btn-primary" disabled>Enregistrer (a venir)</button></div>
        </form>
    </div>
</section>