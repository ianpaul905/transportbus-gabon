import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('nombre_places');
    const total = document.getElementById('total');

    if (input && total) {
        input.addEventListener('input', function () {
            const prix = parseFloat(total.dataset.prix);
            const qte = parseInt(input.value) || 1;
            total.innerHTML = 'Total : <strong>' + (prix * qte).toLocaleString('fr-FR') + ' FCFA</strong>';
        });
    }

    document.querySelectorAll('[data-auto-submit]').forEach(function (select) {
        select.addEventListener('change', function () {
            if (select.form) {
                select.form.submit();
            }
        });
    });

    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        const form = element.tagName === 'FORM' ? element : element.closest('form');

        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            if (!window.confirm(element.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-print]').forEach(function (button) {
        button.addEventListener('click', function () {
            window.print();
        });
    });
});
