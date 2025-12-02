document.addEventListener('DOMContentLoaded', () => {
    const loginUser = document.getElementById('loginUser');
    const loginPassword = document.getElementById('loginPassword');
    const loginBtn = document.getElementById('login-btn');
    const loginForm = document.getElementById('loginForm');

    let users = JSON.parse(localStorage.getItem('users') || '[]');

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

    if (!loginBtn) return console.error("Login button not found");

    loginBtn.addEventListener('click', e => {
        e.preventDefault();

        const userVal = loginUser.value.trim();
        const passVal = loginPassword.value.trim();

        if (!userVal || !passVal)
            return showConsoleAlert("Error", "All fields are required");

        // Match either email or username
        const foundUser = users.find(u =>
            (u.email === userVal || u.username === userVal)
        );

        if (!foundUser)
            return showConsoleAlert("Error", "Account not found");

        if (foundUser.password !== passVal)
            return showConsoleAlert("Error", "Incorrect password");

        // SUCCESS
        showConsoleAlert("Success", "Login successful!", "success");

        // Optional: save session
        localStorage.setItem("currentUser", JSON.stringify(foundUser));

        setTimeout(() => {
            window.location.replace("FindFreelancers.php"); // redirect after login
        }, 1000);
    });
});