document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('form[action*="ingredient.delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet ingrédient ?')) {
                e.preventDefault();
            }
        });
    });

    const icon = document.getElementById('ingredient-icon');
    const message = document.getElementById('ingredient-message');
    if (icon && message) {
        icon.addEventListener('click', () => {
            message.style.display = message.style.display === 'none' ? 'block' : 'none';
        });
    }
});
