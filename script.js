const nextBtn = document.querySelector('.next-btn');
const backArrow = document.querySelector('.back-arrow');
const loginBox = document.querySelector('.login-box');
const altLogin = loginBox.querySelector('.alt-login');
<<<<<<< HEAD
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
=======
const orContinue = loginBox.querySelector('p1');

let usernameInput = loginBox.querySelector('input');
let passwordShown = false;

nextBtn.addEventListener('click', () => {
    if (!passwordShown) {

        const passwordInput = document.createElement('input');
        passwordInput.type = 'password';
        passwordInput.placeholder = 'Password';
        passwordInput.style.width = usernameInput.style.width;
        passwordInput.style.padding = usernameInput.style.padding;
        passwordInput.style.borderRadius = usernameInput.style.borderRadius;
        passwordInput.style.border = usernameInput.style.border;
        passwordInput.style.outline = usernameInput.style.outline;
>>>>>>> 4c539c1b4b80a0482825985eada00a2e465b711c

        loginBox.replaceChild(passwordInput, usernameInput);

        orContinue.style.display = 'none';
        altLogin.style.display = 'none';
        backArrow.style.display = 'block'; 

        passwordShown = true;
    } else {
<<<<<<< HEAD
        // Redirect to frontpage (simulate login success)
=======
>>>>>>> 4c539c1b4b80a0482825985eada00a2e465b711c
        window.location.href = 'frontpage.html';
    }
});

<<<<<<< HEAD
// Back arrow click
backArrow.addEventListener('click', () => {
    if (passwordShown) {
        loginBox.replaceChild(usernameInput, passwordInput);

        orContinue.style.display = 'block';
        altLogin.style.display = 'flex';
        backArrow.style.display = 'none';

        passwordShown = false;
    }
=======
backArrow.addEventListener('click', () => {
    const passwordInput = loginBox.querySelector('input');
    loginBox.replaceChild(usernameInput, passwordInput);

    orContinue.style.display = 'block';
    altLogin.style.display = 'flex';
    backArrow.style.display = 'none';

    passwordShown = false;
>>>>>>> 4c539c1b4b80a0482825985eada00a2e465b711c
});
