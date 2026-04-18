<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y/E Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- login forma -->
<div class="login-box" id="loginBox">
    <h2 class="form-title">Sign in</h2>
    
    <!-- mesazhi error per login-->
    <div class="error-message" id="loginError"></div>
    
    <!-- Username inputi -->
    <input type="text" placeholder="Username ose Email" class="username-input" id="loginUsername">
    
    <!-- Password inputi -->
    <input type="password" placeholder="Password" class="password-input" id="loginPassword">
    
    <p class="forgot" id="forgot-btn">Harruat fjalëkalimin?</p>
    <button class="next-btn" id="loginBtn">Sign In</button>
    
    <p class="switch-link" id="showRegister">Nuk keni llogari? Regjistrohuni</p>
    
    <p class="or-continue">ose vazhdo me</p>
    
    <div class="alt-login">
        <button class="gsi-material-button">
            <div class="gsi-material-button-state"></div>
            <div class="gsi-material-button-content-wrapper">
                <div class="gsi-material-button-icon">
                    <!-- Google SVG icon -->
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                        <path fill="none" d="M0 0h48v48H0z"></path>
                    </svg>
                </div>
                <span class="gsi-material-button-contents">Kyçu me Google</span>
            </div>
        </button>
    </div>
</div>

<!-- forma e regjistrimit -->
<div class="login-box register-box" id="registerBox">
    <h2 class="form-title">Regjistrohu</h2>
    
    <!-- Mesazhi error per regjistrim -->
    <div class="error-message" id="registerError"></div>
    
    <!-- mesazhi sukses per regjistrim -->
    <div class="success-message" id="registerSuccess"></div>
    
    <!-- inputat e regjistrimit -->
    <input type="text" placeholder="Username" class="username-input" id="regUsername">
    <input type="email" placeholder="Email Address" class="username-input" id="regEmail">
    <input type="password" placeholder="Password" class="password-input" id="regPassword">
    <input type="text" placeholder="Emri dhe mbiemri (Opcionale)" class="username-input" id="regFullName">
    
    <select class="country-select" id="regCountry">
        <option value="Kosovo">Kosovo</option>
        <option value="Albania">Albania</option>
        <option value="Other">Other</option>
    </select>
    
    <button class="next-btn" id="registerBtn">Krijo Llogari</button>
    
    <p class="switch-link" id="showLogin">Keni një llogari? Sign in</p>
</div>

<!-- pass reset boxi -->
<div class="reset-box" id="reset-box" style="display:none;">
    <h2>Ndrysho fjalëkalimin</h2>
    <input type="email" id="reset-email" placeholder="Shkruani emailin tuaj">
    <button id="reset-submit">Dërgo linkun per ndryshim</button>
    <p id="reset-back" style="cursor:pointer;">Kthehu</p>
</div>

<div id="reset-popup" style="display:none;" class="popup-reset">
    <div class="popup-content">
        <p>Reset link sent!</p>
        <button id="popup-ok">OK</button>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
