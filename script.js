// ======================
// FORM TOGGLE (Login ↔ Register)
// ======================
const showRegister = document.getElementById('showRegister');
const showLogin = document.getElementById('showLogin');
const loginBox = document.getElementById('loginBox');
const registerBox = document.getElementById('registerBox');

// Show Register form
showRegister.addEventListener('click', () => {
    loginBox.style.display = 'none';
    registerBox.style.display = 'flex';
});

// Show Login form
showLogin.addEventListener('click', () => {
    registerBox.style.display = 'none';
    loginBox.style.display = 'flex';
});

// ======================
// LOGIN FUNCTIONALITY
// ======================
const loginBtn = document.getElementById('loginBtn');
const loginUsername = document.getElementById('loginUsername');
const loginPassword = document.getElementById('loginPassword');
const loginError = document.getElementById('loginError');

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    
    const username = loginUsername.value.trim();
    const password = loginPassword.value.trim();
    
    // Clear previous error
    loginError.style.display = 'none';
    loginError.textContent = '';
    
    // Validation
    if (!username || !password) {
        loginError.textContent = 'Please fill in both username/email and password.';
        loginError.style.display = 'block';
        return;
    }
    
    // Here you would normally make an AJAX request to your backend
    // For now, we'll simulate successful login with hardcoded credentials
    // Replace this with actual backend API call
    
    // TEMPORARY: Check for admin login (for demo)
    if (username === 'admin' && password === 'admin123') {
        alert('Login successful! Redirecting to admin panel...');
        window.location.href = 'frontpage.html';
        return;
    }
    
    // TEMPORARY: Check for test user
    if (username === 'filon' && password === 'user123') {
        alert('Login successful! Redirecting to store...');
        window.location.href = 'frontpage.html';
        return;
    }
    
    // If credentials don't match
    loginError.textContent = 'Invalid username/email or password.';
    loginError.style.display = 'block';
});

// ======================
// REGISTRATION FUNCTIONALITY
// ======================
const registerBtn = document.getElementById('registerBtn');
const regUsername = document.getElementById('regUsername');
const regEmail = document.getElementById('regEmail');
const regPassword = document.getElementById('regPassword');
const regFullName = document.getElementById('regFullName');
const regCountry = document.getElementById('regCountry');
const registerError = document.getElementById('registerError');
const registerSuccess = document.getElementById('registerSuccess');

registerBtn.addEventListener('click', (e) => {
    e.preventDefault();
    
    const username = regUsername.value.trim();
    const email = regEmail.value.trim();
    const password = regPassword.value.trim();
    const fullName = regFullName.value.trim();
    const country = regCountry.value;
    
    // Clear previous messages
    registerError.style.display = 'none';
    registerError.textContent = '';
    registerSuccess.style.display = 'none';
    registerSuccess.textContent = '';
    
    // Validation
    if (!username || !email || !password) {
        registerError.textContent = 'Please fill in all required fields.';
        registerError.style.display = 'block';
        return;
    }
    
    if (username.length < 3) {
        registerError.textContent = 'Username must be at least 3 characters.';
        registerError.style.display = 'block';
        return;
    }
    
    if (password.length < 6) {
        registerError.textContent = 'Password must be at least 6 characters.';
        registerError.style.display = 'block';
        return;
    }
    
    if (!validateEmail(email)) {
        registerError.textContent = 'Please enter a valid email address.';
        registerError.style.display = 'block';
        return;
    }
    
    // Here you would make an AJAX request to your backend registration API
    // For now, simulate successful registration
    
    // TEMPORARY: Store in localStorage for demo
    const userData = {
        username: username,
        email: email,
        password: password, // In real app, hash this!
        fullName: fullName,
        country: country,
        createdAt: new Date().toISOString()
    };
    
    // Save to localStorage (temporary solution)
    localStorage.setItem('currentUser', JSON.stringify(userData));
    
    // Show success message
    registerSuccess.textContent = 'Account created successfully! You can now log in.';
    registerSuccess.style.display = 'block';
    
    // Clear form
    regUsername.value = '';
    regEmail.value = '';
    regPassword.value = '';
    regFullName.value = '';
    regCountry.value = 'Kosovo';
    
    // Auto-switch to login after 2 seconds
    setTimeout(() => {
        registerBox.style.display = 'none';
        loginBox.style.display = 'flex';
        registerSuccess.style.display = 'none';
    }, 2000);
});

// ======================
// PASSWORD RESET FUNCTIONALITY
// ======================
const forgotBtn = document.getElementById("forgot-btn");
const resetBox = document.getElementById("reset-box");
const resetBack = document.getElementById("reset-back");
const resetSubmit = document.getElementById("reset-submit");
const resetEmail = document.getElementById("reset-email");
const popupOk = document.getElementById("popup-ok");
const resetPopup = document.getElementById("reset-popup");

// Show reset box
forgotBtn.addEventListener("click", () => {
    loginBox.style.display = "none";
    resetBox.style.display = "flex";
});

// Back to login
resetBack.addEventListener("click", () => {
    resetBox.style.display = "none";
    loginBox.style.display = "flex";
});

// Submit reset request
resetSubmit.addEventListener("click", () => {
    const email = resetEmail.value.trim();
    
    if (!email || !validateEmail(email)) {
        alert("Please enter a valid email address.");
        return;
    }
    
    // Show success popup
    resetPopup.style.display = "flex";
});

// Close popup
popupOk.addEventListener("click", () => {
    resetPopup.style.display = "none";
    resetBox.style.display = "none";
    loginBox.style.display = "flex";
    resetEmail.value = '';
});

// ======================
// HELPER FUNCTIONS
// ======================
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// ======================
// GOOGLE SIGN-IN (Demo - would need OAuth setup)
// ======================
document.querySelector('.gsi-material-button').addEventListener('click', () => {
    alert('Google Sign-In would be implemented with OAuth 2.0. For demo, use regular login.');
});

// ======================
// ENTER KEY SUPPORT
// ======================
loginUsername.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') loginBtn.click();
});

loginPassword.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') loginBtn.click();
});

regPassword.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') registerBtn.click();
});

resetEmail.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') resetSubmit.click();
});