
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');
const livePreview = document.getElementById('livePreview');
const submitBtn = document.getElementById('submitBtn');


emailInput.addEventListener('input', function() {
    const email = this.value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email === '') {
        emailError.textContent = '';
        emailError.style.color = '#ff6b6b';
    } else if (!emailPattern.test(email)) {
        emailError.textContent = 'Please enter a valid email (e.g., name@gmail.com)';
        emailError.style.color = '#ff6b6b';
    } else {
        emailError.textContent = 'Valid email format!';
        emailError.style.color = '#51cf66';
    }
    
    updateLivePreview();
});


passwordInput.addEventListener('input', function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);
    
    strengthText.textContent = strength.text;
    strengthBar.className = 'strength-level ' + strength.className;
    
    validatePasswordRequirements(password);
    
    updateLivePreview();
});

function checkPasswordStrength(password) {
    let score = 0;
    
    if (password.length === 0) {
        return { text: 'Enter a password', className: '' };
    }
    
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    
    if (/[0-9]/.test(password)) score++;
    
    if (/[A-Z]/.test(password)) score++;
    
    if (/[a-z]/.test(password)) score++;
    
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
    
    if (score <= 2) {
        return { text: ' Weak Password', className: 'weak' };
    } else if (score <= 4) {
        return { text: ' Medium Password', className: 'medium' };
    } else if (score <= 6) {
        return { text: ' Strong Password', className: 'strong' };
    } else {
        return { text: ' Very Strong!', className: 'very-strong' };
    }
}

function validatePasswordRequirements(password) {
    const requirements = [];
    
    if (password.length > 0 && password.length < 8) {
        requirements.push('at least 8 characters');
    }
    if (password.length > 0 && !/[0-9]/.test(password)) {
        requirements.push('a number');
    }
    if (password.length > 0 && !/[A-Z]/.test(password)) {
        requirements.push('an uppercase letter');
    }
    
    if (requirements.length > 0 && password.length > 0) {
        passwordError.textContent = 'Needs: ' + requirements.join(', ');
        passwordError.style.color = '#ffd43b';
    } else if (password.length >= 8 && /[0-9]/.test(password) && /[A-Z]/.test(password)) {
        passwordError.textContent = ' Password meets requirements!';
        passwordError.style.color = '#51cf66';
    } else if (password.length === 0) {
        passwordError.textContent = '';
    }
}

function updateLivePreview() {
    const email = emailInput.value || 'Not entered yet';
    const password = passwordInput.value || 'Not entered yet';
    
    livePreview.innerHTML = `
        <strong> Email:</strong> ${email}<br>
        <strong> Password:</strong> ${maskPassword(password)}<br>
        <small style="color: #aaa;">Live preview updates as you type</small>
    `;
}

function maskPassword(password) {
    if (password === 'Not entered yet' || password === '') {
        return 'Not entered yet';
    }
    let masked = '';
    for (let i = 0; i < password.length; i++) {
        masked += '•';
    }
    return masked;
}

submitBtn.addEventListener('click', function(e) {
    let isValid = true;
    
    const email = emailInput.value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email === '') {
        emailError.textContent = ' Email is required';
        emailError.style.color = '#ff6b6b';
        isValid = false;
    } else if (!emailPattern.test(email)) {
        emailError.textContent = ' Please enter a valid email';
        emailError.style.color = '#ff6b6b';
        isValid = false;
    }
    
    const password = passwordInput.value;
    if (password === '') {
        passwordError.textContent = ' Password is required';
        passwordError.style.color = '#ff6b6b';
        isValid = false;
    } else if (password.length < 8) {
        passwordError.textContent = ' Password must be at least 8 characters';
        passwordError.style.color = '#ff6b6b';
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fix the errors before submitting.');
    }
});