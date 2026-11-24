// Remplit la fenêtre modale avec les données du bouton "Détails" cliqué.
const modal = document.getElementById('detailsModal');

if (modal) {
    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;

        modal.querySelector('[data-field="identite"]').textContent = button.dataset.identite;
        modal.querySelector('[data-field="telephone"]').textContent = button.dataset.telephone;
        modal.querySelector('[data-field="email"]').textContent = button.dataset.email;
        modal.querySelector('[data-field="places-total"]').textContent = button.dataset.placesTotal;
    });
}