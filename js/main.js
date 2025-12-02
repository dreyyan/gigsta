// Helper functions for input validation
export function isEmail(v) {
    return /\S+@\S+\.\S+/.test(v);
}
export function minLength(v, n = 6) {
    return v.length >= n;
}
export function passwordsMatch(a, b) {
    return a === b;
}

// Shows or clears messages on the screen
export function showMessage(element, text, type = 'error') {
    element.innerText = text;
    element.className = type;
}
export function clearMessage(element) {
    element.innerText = '';
    element.className = '';
}

// Load Existing Users
let users = JSON.parse(localStorage.getItem('users') || '[]');

// Save Users
function saveUsers() {
    localStorage.setItem('users', JSON.stringify(users));
}

// Register/Sign Up Functionality
// Get the sign up form and message area
const signUpForm = document.getElementById('signUpForm');
const signUpMessage = document.getElementById('signUpMessage');

// When the user clicks Register/Sign Up
signUpForm.addEventListener('submit', (e) => {
    e.preventDefault();
    clearMessage(signUpMessage);

    // Collects the values entered in the form 
    const username = document.getElementById('signUpUsername').value.trim();
    const email = document.getElementById('signUpEmail').value.trim();
    const password = document.getElementById('signUpPassword').value;
    const confirm = document.getElementById('signUpConfirm').value;
});

// Input Validation for Register/Sign Up
if (!username || !email || !password || !confirm) {
    return showMessage(signUpMessage, "All Fields are Required.");
}
if (!isEmail(email)) {
    return showMessage(signUpMessage, "Invalid Email.");
}
if (!minLength(password, 6)) {
    return showMessage(signUpMessage, "Password must be at least 6 characters.");
}
if (!passwordsMatch(password, confirm)) {
    return showMessage(signUpMessage, "Password do not match.");
}

// Check for Duplicates
if (users.some(u => u.username === username)) {
    return showMessage(signUpMessage, "Username Already Taken.");
}
if (users.some(u => u.email === email)) {
    return showMessage(signUpMessage, "Email Already Registered.");
}

// Save User
users.push({ username, email, password });
saveUsers();

// Show Success Message
showMessage(signUpMessage, "Registration Successful!", 'success');
signUpForm.reset();

// Login Functionality
// Get login form
const loginForm = document.getElementById('loginForm');
const loginMessage = document.getElementById('loginMessage');

// When the user clicks Login
loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    clearMessage(loginMessage);

    // Collects the values entered in the form 
    const userInput = document.getElementById('loginUser').value.trim();
    const password = document.getElementById('loginPassword').value;
});

// Allow user to login using either username or email
const user = users.find(u => (u.username === userInput || u.email === userInput));

// Validate Login Details
if (!user) return showMessage(loginMessage, "User not found.");
if (user.password !== password) return showMessage(loginMessage, "Incorrect Password.");

// Show Success Message
showMessage(loginMessage, "Login Successful!", 'success');
loginForm.reset();

