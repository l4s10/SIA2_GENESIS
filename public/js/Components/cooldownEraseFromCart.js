document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.btn-danger');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.disabled = true;
            button.closest('form').submit();
        });
    });
});

