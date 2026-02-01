// zgjedhja mes login formes edhe register
const showRegister = document.getElementById('showRegister');
const showLogin = document.getElementById('showLogin');
const loginBox = document.getElementById('loginBox');
const registerBox = document.getElementById('registerBox');

// kallxo register form
showRegister.addEventListener('click', () => {
    loginBox.style.display = 'none';
    registerBox.style.display = 'flex';
});

// kallxo login form
showLogin.addEventListener('click', () => {
    registerBox.style.display = 'none';
    loginBox.style.display = 'flex';
});


// funksioni i loginit

const loginBtn = document.getElementById('loginBtn');
const loginUsername = document.getElementById('loginUsername');
const loginPassword = document.getElementById('loginPassword');
const loginError = document.getElementById('loginError');

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    
    const username = loginUsername.value.trim();
    const password = loginPassword.value.trim();
    
    // fshij errorin e meparshem
    loginError.style.display = 'none';
    loginError.textContent = '';
    
    // validimi
    if (!username || !password) {
        loginError.textContent = 'Please fill in both username/email and password.';
        loginError.style.display = 'block';
        return;
    }
    
    // dergo te dhenat ne server me AJAX per te vendosur session
    fetch('ajax_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.role === 'admin') {
                alert('Login successful! Redirecting to admin panel...');
                window.location.href = 'admin_dashboard.php';
            } else {
                alert('Login successful! Redirecting to store...');
                window.location.href = 'frontpage.php';
            }
        } else {
            loginError.textContent = data.message || 'Invalid username/email or password.';
            loginError.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loginError.textContent = 'Login failed. Please try again.';
        loginError.style.display = 'block';
    });
});


// funksioni i regjistrimit

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
    
    // fshij mesazhet e meparshme
    registerError.style.display = 'none';
    registerError.textContent = '';
    registerSuccess.style.display = 'none';
    registerSuccess.textContent = '';
    
    // validimi
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
    
    // dergo te dhenat e regjistrimit ne server per database
    fetch('ajax_register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&full_name=${encodeURIComponent(fullName)}&country=${encodeURIComponent(country)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // trego mesazhin e suksesit
            registerSuccess.textContent = data.message;
            registerSuccess.style.display = 'block';
            
            // forma e paprekur
            regUsername.value = '';
            regEmail.value = '';
            regPassword.value = '';
            regFullName.value = '';
            regCountry.value = 'Kosovo';
            
            // ndrroje automatikisht ne login pas nje kohe
            setTimeout(() => {
                registerBox.style.display = 'none';
                loginBox.style.display = 'flex';
                registerSuccess.style.display = 'none';
            }, 2000);
        } else {
            registerError.textContent = data.message;
            registerError.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        registerError.textContent = 'Registration failed. Please try again.';
        registerError.style.display = 'block';
    });
});


// funksioni i password reset

const forgotBtn = document.getElementById("forgot-btn");
const resetBox = document.getElementById("reset-box");
const resetBack = document.getElementById("reset-back");
const resetSubmit = document.getElementById("reset-submit");
const resetEmail = document.getElementById("reset-email");
const popupOk = document.getElementById("popup-ok");
const resetPopup = document.getElementById("reset-popup");

// trego reset box
forgotBtn.addEventListener("click", () => {
    loginBox.style.display = "none";
    resetBox.style.display = "flex";
});

// kthehu te logini
resetBack.addEventListener("click", () => {
    resetBox.style.display = "none";
    loginBox.style.display = "flex";
});

// bone submit requestin
resetSubmit.addEventListener("click", () => {
    const email = resetEmail.value.trim();
    
    if (!email || !validateEmail(email)) {
        alert("Please enter a valid email address.");
        return;
    }
    
    // kallxo popupin t'suksesit
    resetPopup.style.display = "flex";
});

// mbyll popup
popupOk.addEventListener("click", () => {
    resetPopup.style.display = "none";
    resetBox.style.display = "none";
    loginBox.style.display = "flex";
    resetEmail.value = '';
});


// funksionet ndihmse

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}


// google sign-in (Demo)

document.querySelector('.gsi-material-button').addEventListener('click', () => {
    alert('Google Sign-In would be implemented with OAuth 2.0. For demo, use regular login.');
});


// butoni enter ndihms
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