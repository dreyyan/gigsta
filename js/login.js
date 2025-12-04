document.addEventListener('DOMContentLoaded', () => {
    const loginUser = document.getElementById('loginUser');
    const loginPassword = document.getElementById('loginPassword');
    const loginBtn = document.getElementById('login-btn');
    const loginForm = document.getElementById('loginForm');

    let users = JSON.parse(localStorage.getItem('users') || '[]');

    async function showConsoleAlert(title, message, type = 'error') {
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

    loginBtn.addEventListener('click', async e => {
        e.preventDefault();

        const userVal = loginUser.value.trim();
        const passVal = loginPassword.value.trim();

        if (!userVal || !passVal)
            return showConsoleAlert("Error", "All fields are required");

        let response;
        try {
            response = await fetch("http://localhost:8000/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ identifier: userVal, password: passVal })
            });
        } catch (error) {
            return showConsoleAlert("Error", "Cannot connect to server.");
        }

        const result = await response.json();

        if (result.status !== "success") {
            return showConsoleAlert("Error", result.message || "Login failed.");
        }

        showConsoleAlert("Success", "Login successful!", "success");

        localStorage.setItem("currentUser", JSON.stringify({
            identifier: userVal
        }));

        setTimeout(() => {
            window.location.replace("FindFreelancers.php"); // redirect after login
        }, 1000);
    });
});