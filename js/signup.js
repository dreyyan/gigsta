document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const primaryBtn = document.getElementById('primary-btn');

    if (primaryBtn) {
        primaryBtn.addEventListener('click', (e) => {
            e.preventDefault(); // prevent form submission on step 1
            step1.style.display = 'none';
            step2.style.display = 'block';

            // focus first input in step 2
            const firstInput = step2.querySelector('input');
            if (firstInput) firstInput.focus();
        });
    }
});