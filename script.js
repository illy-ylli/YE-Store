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

        // Replace username with password input
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
const rows = document.querySelectorAll('.products-row, .product-row');

rows.forEach(row => {
  let isDown = false;
  let startX;
  let scrollLeft;

  row.addEventListener('mousedown', e => {
    isDown = true;
    row.classList.add('active');
    startX = e.pageX - row.offsetLeft;
    scrollLeft = row.scrollLeft;
  });
  row.addEventListener('mouseleave', () => {
    isDown = false;
    row.classList.remove('active');
  });
  row.addEventListener('mouseup', () => {
    isDown = false;
    row.classList.remove('active');
  });
  row.addEventListener('mousemove', e => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - row.offsetLeft;
    const walk = (x - startX) * 2; // scroll speed
    row.scrollLeft = scrollLeft - walk;
  });
});

const forgotBtn = document.getElementById("forgot-btn");
const resetBox = document.getElementById("reset-box");
const resetBack = document.getElementById("reset-back");
const resetSubmit = document.getElementById("reset-submit");
const loginBoxDiv = document.querySelector(".login-box");

// OPEN reset screen
forgotBtn.addEventListener("click", () => {
    loginBoxDiv.style.display = "none";
    resetBox.style.display = "flex";
});

// Kthehu te login
resetBack.addEventListener("click", () => {
    resetBox.style.display = "none";
    loginBoxDiv.style.display = "flex";
});

resetSubmit.addEventListener("click", () => {

    let email = document.getElementById("reset-email").value;

    if(!email.includes("@")){
        alert("Enter a valid email.");
        return;
    }

    document.getElementById("reset-popup").style.display = "flex";
});
document.getElementById("popup-ok").addEventListener("click", () => {
    document.getElementById("reset-popup").style.display = "none";
    resetBox.style.display = "none";
    loginBoxDiv.style.display = "flex";
});