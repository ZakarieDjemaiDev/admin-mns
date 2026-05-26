document.addEventListener('DOMContentLoaded', function () {
    var deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var message = form.getAttribute('data-confirm') || 'Confirmer la suppression ?';
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
    });

    var trainingDuration = document.getElementById('duration_months');
    var trainingStart = document.getElementById('start_date');
    var trainingEnd = document.getElementById('end_date_preview');

    function updateEndPreview() {
        if (!trainingEnd || !trainingStart || !trainingDuration) return;
        var months = parseInt(trainingDuration.value, 10);
        var start = trainingStart.value;
        if (!start || !months || months <= 0) {
            trainingEnd.textContent = '—';
            return;
        }
        var d = new Date(start);
        d.setMonth(d.getMonth() + months);
        trainingEnd.textContent = d.toISOString().slice(0, 10);
    }

    if (trainingDuration) trainingDuration.addEventListener('input', updateEndPreview);
    if (trainingStart) trainingStart.addEventListener('change', updateEndPreview);
    updateEndPreview();
});
