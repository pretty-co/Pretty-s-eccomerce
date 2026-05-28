<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretty's - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lucida Calligraphy', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #FFF5F5 0%, #FFF0F5 25%, #F0F4FF 50%, #FFF5E6 100%);
        }

        body::before {
            content: "✨";
            position: absolute;
            top: 10%;
            left: 5%;
            font-size: 60px;
            color: rgba(212, 175, 55, 0.15);
            font-family: serif;
        }

        body::after {
            content: "✨";
            position: absolute;
            bottom: 10%;
            right: 5%;
            font-size: 80px;
            color: rgba(212, 175, 55, 0.12);
            font-family: serif;
        }

        .floating-flower {
            position: absolute;
            font-size: 40px;
            opacity: 0.08;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            padding: 48px 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04), 0 8px 16px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 400;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #D4AF37 0%, #C49B0F 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .logo .sparkle {
            font-size: 24px;
            color: #D4AF37;
            margin: 0 4px;
        }

        .welcome-text {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 8px;
            font-weight: 300;
        }

        .error-banner {
            background: #FFF0F0;
            border: 1px solid #FFCDCD;
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }

        .error-banner p {
            color: #D32F2F;
            font-size: 13px;
            margin: 4px 0;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            color: #333;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            background: #F8F8F8;
            border: 1px solid #E8E8E8;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .input-group input:focus {
            outline: none;
            border-color: #D4AF37;
            background: white;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .input-group input.input-error {
            border-color: #D32F2F;
        }

        .error-message {
            color: #D32F2F;
            font-size: 11px;
            margin-top: 6px;
            display: block;
        }

        .strength-container {
            margin-bottom: 20px;
        }

        .strength-container p {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .strength-bar {
            height: 4px;
            background: #E8E8E8;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-level {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #FF6B6B; width: 25%; }
        .strength-medium { background: #FFB347; width: 50%; }
        .strength-strong { background: #4CAF50; width: 75%; }
        .strength-very-strong { background: #D4AF37; width: 100%; }

        .live-preview {
            background: #F8F8F8;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 24px;
            border: 1px solid #EEEEEE;
        }


        .checkbox-group {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #D4AF37;
        }

        .checkbox-group label {
            color: #555;
            font-size: 13px;
            cursor: pointer;
        }

        .checkbox-group small {
            color: #999;
            font-size: 11px;
            margin-left: auto;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #D4AF37 0%, #C49B0F 100%);
            border: none;
            border-radius: 40px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .links-row {
            display: flex;
            justify-content: space-between;
            margin: 24px 0 20px;
        }

        .links-row a {
            color: #D4AF37;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .links-row a:hover {
            color: #B8960F;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 24px 0;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #E8E8E8;
        }

        .divider span {
            background: white;
            padding: 0 16px;
            position: relative;
            color: #999;
            font-size: 12px;
        }

        .btn-secondary {
            width: 100%;
            padding: 14px;
            background: transparent;
            border: 1.5px solid #D4AF37;
            border-radius: 40px;
            color: #D4AF37;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: rgba(212, 175, 55, 0.05);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="floating-flower" style="top: 15%; left: 85%;">🌸</div>
    <div class="floating-flower" style="top: 70%; left: 10%;">🌿</div>
    <div class="floating-flower" style="top: 40%; left: 92%;">✨</div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <span class="sparkle">✨</span>
                <h1>PRETTY'S</h1>
                <span class="sparkle">✨</span>
                <p class="welcome-text">Passion for fashion</p>
            </div>

            <?php if (isset($_SESSION['errors'])): ?>
                <div class="error-banner">
                    <?php 
                        foreach($_SESSION['errors'] as $error) {
                            echo "<p> $error</p>";
                        }
                        unset($_SESSION['errors']);
                    ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="form-processor.php" method="POST">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="sample@gmail.com" required>
                    <small class="error-message" id="emailError"></small>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                    <div class="strength-container">
                        <p>Password Strength: <span id="strengthText">Not entered</span></p>
                        <div class="strength-bar">
                            <div id="strengthBar" class="strength-level"></div>
                        </div>
                    </div>
                    <small class="error-message" id="passwordError"></small>
                </div>


                <div class="checkbox-group">
                    <input type="checkbox" id="newsletter" name="newsletter">
                    <label for="newsletter">Sign up for fashion updates & exclusive offers</label>
                </div>
                <div class="links-row">
                    <a href="#"> Forgot password?</a>
                </div>

                <button type="submit" id="submitBtn" class="btn-primary">Sign In </button>
                <div class="divider">
                    <span>new to Pretty's?</span>
                </div>

                
                <a href="#" class="btn-secondary"> Create Account</a>
            </form>
        </div>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const strengthText = document.getElementById('strengthText');
        const strengthBar = document.getElementById('strengthBar');
        const livePreview = document.getElementById('livePreview');

        
        emailInput.addEventListener('input', function() {
            const email = this.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email === '') {
                emailError.textContent = '';
                emailError.style.color = '#D32F2F';
            } else if (!emailPattern.test(email)) {
                emailError.textContent = ' Please enter a valid email address';
                emailError.style.color = '#D32F2F';
            } else {
                emailError.textContent = ' Valid email!';
                emailError.style.color = '#4CAF50';
            }
            updateLivePreview();
        });

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkStrength(password);
            
            strengthText.textContent = strength.text;
            strengthBar.className = 'strength-level ' + strength.class;
            
            
            if (password.length > 0 && password.length < 8) {
                passwordError.textContent = ' Password needs at least 8 characters';
                passwordError.style.color = '#FFB347';
            } else if (password.length >= 8 && !/[0-9]/.test(password)) {
                passwordError.textContent = ' Add a number for extra strength';
                passwordError.style.color = '#FFB347';
            } else if (password.length >= 8 && /[0-9]/.test(password) && /[A-Z]/.test(password)) {
                passwordError.textContent = ' Strong password!';
                passwordError.style.color = '#4CAF50';
            } else if (password.length === 0) {
                passwordError.textContent = '';
            } else {
                passwordError.textContent = '';
            }
            
            updateLivePreview();
        });

        function checkStrength(password) {
            let score = 0;
            
            if (password.length === 0) {
                return { text: 'Not entered', class: '' };
            }
            
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[!@#$%^&*()]/.test(password)) score++;
            
            if (score <= 2) return { text: 'Weak ', class: 'strength-weak' };
            if (score <= 4) return { text: 'Medium ', class: 'strength-medium' };
            if (score <= 6) return { text: 'Strong ', class: 'strength-strong' };
            return { text: 'Very Strong ', class: 'strength-very-strong' };
        }

        function updateLivePreview() {
            const email = emailInput.value || 'Not entered yet';
            const password = passwordInput.value || 'Not entered yet';
            
            let displayPassword = password;
            if (displayPassword !== 'Not entered yet' && displayPassword !== '') {
                let masked = '';
                for (let i = 0; i < displayPassword.length; i++) {
                    masked += '•';
                }
                displayPassword = masked;
            }
            
            livePreview.innerHTML = `
                <strong>✉️ Email:</strong> ${email}<br>
                <strong> Password:</strong> ${displayPassword}<br>
                <span style="font-size: 11px; color: #999;">💡 Tip: Use 8+ chars with numbers</span>
            `;
        }
    </script>
</body>
</html>