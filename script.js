const nextBtn = document.querySelector('.next-btn');
const backArrow = document.querySelector('.back-arrow');
const loginBox = document.querySelector('.login-box');
const altLogin = loginBox.querySelector('.alt-login');
const orContinue = loginBox.querySelector('.or-continue');

const usernameInput = loginBox.querySelector('.username-input');

let passwordShown = false;
let passwordInput = null;

// Next button click
nextBtn.addEventListener('click', () => {
    if (!passwordShown) {
        // Create password input once
        passwordInput = document.createElement('input');
        passwordInput.type = 'password';
        passwordInput.placeholder = 'Password';
        passwordInput.className = 'password-input';

        // Copy styles from username input
        const style = getComputedStyle(usernameInput);
        passwordInput.style.width = style.width;
        passwordInput.style.padding = style.padding;
        passwordInput.style.borderRadius = style.borderRadius;
        passwordInput.style.border = style.border;
        passwordInput.style.outline = style.outline;

        loginBox.replaceChild(passwordInput, usernameInput);

        orContinue.style.display = 'none';
        altLogin.style.display = 'none';
        backArrow.style.display = 'block'; 

        passwordShown = true;
    } else {
        // Redirect to frontpage (simulate login success)
        window.location.href = 'frontpage.html';
    }
});

// Back arrow click
backArrow.addEventListener('click', () => {
    if (passwordShown) {
        loginBox.replaceChild(usernameInput, passwordInput);

        orContinue.style.display = 'block';
        altLogin.style.display = 'flex';
        backArrow.style.display = 'none';

        passwordShown = false;
    }
});
