document.addEventListener('DOMContentLoaded', function () {
    // Recherche tous les formulaires contenant l'attribut data-confirm.
    var deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            // Demande une confirmation avant l'envoi d'une suppression.
            var message = form.getAttribute('data-confirm') || 'Confirmer la suppression ?';
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Si la page contient le formulaire de formation, on met à jour l'aperçu de la date de fin.
    var trainingDuration = document.getElementById('duration_months');
    var trainingStart = document.getElementById('start_date');
    var trainingEnd = document.getElementById('end_date_preview');

    function updateEndPreview() {
        if (!trainingEnd || !trainingStart || !trainingDuration) return;

        var months = parseInt(trainingDuration.value, 10);
        var start = trainingStart.value;

        // Si la date de début ou la durée n'est pas valide, on affiche un tiret.
        if (!start || !months || months <= 0) {
            trainingEnd.textContent = '—';
            return;
        }

        // Ajuste le mois de fin en ajoutant la durée au début.
        var d = new Date(start);
        d.setMonth(d.getMonth() + months);
        trainingEnd.textContent = d.toISOString().slice(0, 10);
    }

    if (trainingDuration) trainingDuration.addEventListener('input', updateEndPreview);
    if (trainingStart) trainingStart.addEventListener('change', updateEndPreview);
    updateEndPreview();
});
