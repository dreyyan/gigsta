document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('signUpPassword');
    const confirmInput = document.getElementById('signUpConfirm');
    const emailInput = document.getElementById('signUpEmail');
    const usernameInput = document.getElementById('signUpUsername');
    const requirements = document.querySelectorAll('.password-requirements li');
    const primaryBtn = document.getElementById('primary-btn');
    const signUpForm = document.getElementById('signUpForm');

    // Email error message container
    const emailError = document.createElement('div');
    emailError.style.color = 'red';
    emailError.style.marginTop = '5px';
    emailInput.parentNode.appendChild(emailError);

    // Live email check
    let debounce;
    emailInput.addEventListener('input', () => {
        clearTimeout(debounce);
        const email = emailInput.value.trim();
        if (!email) {
            emailError.textContent = '';
            return;
        }
        debounce = setTimeout(() => {
            fetch(`SignUp.php?check_email=1&email=${encodeURIComponent(email)}`)
                .then(res => res.json())
                .then(data => {
                    emailError.textContent = data.exists ? 'This email is already taken.' : '';
                })
                .catch(err => console.error(err));
        }, 400);
    });

    // Password requirement check
    if (passwordInput && requirements.length) {
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            requirements.forEach((req) => {
                const img = req.querySelector('img');
                let fulfilled = false;
                switch (req.dataset.rule) {
                    case 'length': fulfilled = value.length >= 8; break;
                    case 'uppercase': fulfilled = /[A-Z]/.test(value); break;
                    case 'lowercase': fulfilled = /[a-z]/.test(value); break;
                    case 'number': fulfilled = /[0-9]/.test(value); break;
                }
                if (img) {
                    img.src = fulfilled
                        ? '../images/check-indicator-checked-icon.svg'
                        : '../images/check-indicator-icon.svg';
                }
            });
        });
    }

    // Show alerts
    function showConsoleAlert(title, message, type = 'error') {
        const existing = document.getElementById('console-alert-modal');
        if (existing) existing.remove();

        const modal = document.createElement('div');
        modal.id = 'console-alert-modal';
        modal.style.position = 'fixed';
        modal.style.top = '20px';
        modal.style.right = '20px';
        modal.style.width = '300px';
        modal.style.padding = '16px 20px';
        modal.style.backgroundColor = '#fff';
        modal.style.borderLeft = `6px solid ${type === 'error' ? '#FF4C4C' : '#28A745'}`;
        modal.style.borderRadius = '8px';
        modal.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        modal.style.fontFamily = 'DM Sans, sans-serif';
        modal.style.color = '#333';
        modal.style.zIndex = '9999';
        modal.style.animation = 'slideIn 0.3s ease';

        const h5 = document.createElement('h5');
        h5.innerText = title.toUpperCase();
        h5.style.margin = '0 0 8px 0';
        h5.style.fontSize = '17px';
        h5.style.fontWeight = '700';
        h5.style.color = type === 'error' ? '#FF4C4C' : '#28A745';
        modal.appendChild(h5);

        const p = document.createElement('p');
        p.innerText = message;
        p.style.margin = '0';
        p.style.fontSize = '15px';
        modal.appendChild(p);

        const btn = document.createElement('span');
        btn.innerText = '×';
        btn.style.position = 'absolute';
        btn.style.top = '0px';
        btn.style.right = '12px';
        btn.style.cursor = 'pointer';
        btn.style.fontSize = '28px';
        btn.style.fontWeight = '700';
        btn.addEventListener('click', () => modal.remove());
        modal.appendChild(btn);

        document.body.appendChild(modal);
        setTimeout(() => { if (modal.parentNode) modal.remove(); }, 4000);

        if (type === 'error') console.error(message);
        else console.log(message);
    }

    // AJAX: Check if email exists
    async function checkEmailExists(email) {
        try {
            const res = await fetch(`SignUp.php?check_email=1&email=${encodeURIComponent(email)}`);
            const data = await res.json();
            return data.exists;
        } catch { return false; }
    }

    // AJAX: Check if username exists
    async function checkUsernameExists(username) {
        try {
            const formData = new FormData();
            formData.append('checkUsername', username);
            const res = await fetch('SignUp.php', { method: 'POST', body: formData });
            const data = await res.json();
            return data.exists;
        } catch { return false; }
    }

    // Submit signup form
    if (primaryBtn) {
        primaryBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            const email = emailInput.value.trim();
            const username = usernameInput.value.trim();
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            // Client-side validation
            if (!email || !username || !password || !confirm) {
                return showConsoleAlert("Error", "All fields are required");
            }
            if (password.length < 8) {
                return showConsoleAlert("Error", "Password must be at least 8 characters");
            }
            if (password !== confirm) {
                return showConsoleAlert("Error", "Passwords do not match");
            }

            try {
                const formData = new FormData();
                formData.append('signUpEmail', email);
                formData.append('signUpUsername', username);
                formData.append('signUpPassword', password);
                formData.append('signUpConfirm', confirm);

                const res = await fetch('SignUp.php', {
                    method: 'POST',
                    body: formData
                });

                // Only attempt JSON parse if response is JSON
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error("Server did not return JSON");
                }

                const data = await res.json();

                if (data.status === 'error') {
                    return showConsoleAlert("Error", data.message);
                }

                showConsoleAlert("Success", data.message, "success");
                signUpForm.reset();
                window.location.replace("Login.php");
            } catch (err) {
                showConsoleAlert("Error", "Registration failed");
                console.error(err);
            }
        });
    }
});