document.addEventListener('DOMContentLoaded', () => {
    const submitButtons = document.querySelectorAll('button[type="submit"]');

    submitButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.disabled = true;
            button.closest('form').submit();
        });
    });
});

