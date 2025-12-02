document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('signUpPassword');
    const confirmInput = document.getElementById('signUpConfirm');
    const emailInput = document.getElementById('signUpEmail');
    const usernameInput = document.getElementById('signUpUsername');
    const requirements = document.querySelectorAll('.password-requirements li');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const primaryBtn = document.getElementById('primary-btn');
    const primaryBtn2 = document.getElementById('primary-btn2');
    const signUpForm = document.getElementById('signUpForm');

    let users = JSON.parse(localStorage.getItem('users') || '[]');
    function saveUsers() {
        localStorage.setItem('users', JSON.stringify(users));
    }

    if (!passwordInput) console.warn("Password input not found");
    if (!requirements.length) console.warn("Password requirements not found");

    if (passwordInput && requirements.length) {
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            requirements.forEach((req) => {
                const img = req.querySelector('img');
                let fulfilled = false;
                switch (req.dataset.rule) {
                    case 'length':
                        fulfilled = value.length >= 8;
                        break;
                    case 'uppercase':
                        fulfilled = /[A-Z]/.test(value);
                        break;
                    case 'lowercase':
                        fulfilled = /[a-z]/.test(value);
                        break;
                    case 'number':
                        fulfilled = /[0-9]/.test(value);
                        break;
                }
                if (img) {
                    img.src = fulfilled
                        ? '../images/check-indicator-checked-icon.svg'
                        : '../images/check-indicator-icon.svg';
                } else {
                    console.warn("Image element not found for", req.dataset.rule);
                }
            });
        });
    }

    function isEmail(v) { return /\S+@\S+\.\S+/.test(v); }
    function minLength(v, n = 6) { return v.length >= n; }
    function passwordsMatch(a, b) { return a === b; }

    function showConsoleAlert(title, message, type = 'error') {
        const existing = document.getElementById('console-alert-modal');
        if (existing) existing.remove();

        // Create modal container (toast style)
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

        // Title
        const h5 = document.createElement('h5');
        h5.innerText = title.toUpperCase();
        h5.style.margin = '0 0 8px 0';
        h5.style.fontSize = '17px';
        h5.style.fontWeight = '700';
        h5.style.color = type === 'error' ? '#FF4C4C' : '#28A745';
        modal.appendChild(h5);

        // Message
        const p = document.createElement('p');
        p.innerText = message;
        p.style.margin = '0';
        p.style.fontSize = '15px';
        modal.appendChild(p);

        // Close button
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

        // Auto-dismiss after 4 seconds
        setTimeout(() => {
            if (modal.parentNode) modal.remove();
        }, 4000);

        if (type === 'error') console.error(message);
        else console.log(message);
    }

    if (primaryBtn) {
        primaryBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            if (!email || !password || !confirm) return showConsoleAlert("Error", "All fields required");
            if (!isEmail(email)) return showConsoleAlert("Error", "Invalid email");
            if (!minLength(password, 8)) return showConsoleAlert("Error", "Password must be at least 8 characters");
            if (!passwordsMatch(password, confirm)) return showConsoleAlert("Error", "Passwords do not match");
            step1.style.display = 'none';
            step2.style.display = 'block';
            if (usernameInput) usernameInput.focus();
        });
    }

    if (primaryBtn2) {
        primaryBtn2.addEventListener('click', (e) => {
            e.preventDefault();
            const username = usernameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value;

            if (!username || !email || !password)
                return showConsoleAlert("Error", "All fields are required");

            if (!isEmail(email))
                return showConsoleAlert("Error", "Invalid email");

            if (!minLength(password, 8))
                return showConsoleAlert("Error", "Password must be at least 8 characters");

            if (users.some(u => u.username === username))
                return showConsoleAlert("Error", "Username already taken");

            if (users.some(u => u.email === email))
                return showConsoleAlert("Error", "Email already registered");

            // Save the new user
            users.push({ username, email, password });
            saveUsers();

            // Show success message using the console-style modal
            showConsoleAlert("Success", "Registration successful!", "success");

            // Reset form
            signUpForm.reset();
            step1.style.display = 'block';
            step2.style.display = 'none';

            // Redirect to SignIn.php after a short delay
            setTimeout(() => {
                window.location.replace("SignIn.php");
            }, 1000); // 1 second delay
        });
    }
});
