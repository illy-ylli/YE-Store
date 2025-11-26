const nextBtn = document.querySelector('.next-btn');
const backArrow = document.querySelector('.back-arrow');
const loginBox = document.querySelector('.login-box');
const altLogin = loginBox.querySelector('.alt-login');
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

        loginBox.replaceChild(passwordInput, usernameInput);

        orContinue.style.display = 'none';
        altLogin.style.display = 'none';
        backArrow.style.display = 'block'; 

        passwordShown = true;
    } else {
        window.location.href = 'frontpage.html';
    }
});

backArrow.addEventListener('click', () => {
    const passwordInput = loginBox.querySelector('input');
    loginBox.replaceChild(usernameInput, passwordInput);

    orContinue.style.display = 'block';
    altLogin.style.display = 'flex';
    backArrow.style.display = 'none';

    passwordShown = false;
});
