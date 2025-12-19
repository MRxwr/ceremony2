<!-- Client Registration View with WhatsApp Verification -->
<style>
.auth-container {
    max-width: 450px;
    margin: 50px auto;
    padding: 20px;
}

.auth-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-logo {
    font-size: 64px;
    margin-bottom: 15px;
}

.auth-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.auth-subtitle {
    color: #666;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
}

.phone-input-group {
    display: flex;
    gap: 10px;
}

.country-code {
    width: 100px;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
}

.verification-step {
    display: none;
}

.verification-step.active {
    display: block;
}

.code-inputs {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 20px 0;
}

.code-input {
    width: 50px;
    height: 60px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    border: 2px solid #e9ecef;
    border-radius: 12px;
}

.code-input:focus {
    border-color: #667eea;
    outline: none;
}

.resend-timer {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin: 15px 0;
}

.btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
}

.btn-secondary {
    background: #f8f9fa;
    color: #333;
    margin-top: 10px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.auth-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e9ecef;
}

.auth-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.alert {
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.password-strength {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-top: 5px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    transition: all 0.3s;
}

.strength-weak { width: 33%; background: #dc3545; }
.strength-medium { width: 66%; background: #ffc107; }
.strength-strong { width: 100%; background: #28a745; }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">🎉</div>
            <h1 class="auth-title"><?php echo direction("Create Account", "إنشاء حساب") ?></h1>
            <p class="auth-subtitle"><?php echo direction("Join our loyalty program", "انضم إلى برنامج الولاء") ?></p>
        </div>

        <div id="alertBox"></div>

        <!-- Step 1: Phone Number -->
        <div class="verification-step active" id="step1">
            <form id="phoneForm">
                <div class="form-group">
                    <label class="form-label"><?php echo direction("Phone Number", "رقم الهاتف") ?></label>
                    <div class="phone-input-group">
                        <select class="country-code" id="countryCode">
                            <option value="+965">+965 KW</option>
                            <option value="+966">+966 SA</option>
                            <option value="+971">+971 AE</option>
                            <option value="+973">+973 BH</option>
                            <option value="+974">+974 QA</option>
                        </select>
                        <input type="tel" class="form-input" id="phoneNumber" 
                               placeholder="<?php echo direction('12345678', '12345678') ?>" 
                               pattern="[0-9]{8}" maxlength="8" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="sendCodeBtn">
                    <?php echo direction("Send Verification Code", "إرسال رمز التحقق") ?>
                </button>
            </form>
        </div>

        <!-- Step 2: Verify Code -->
        <div class="verification-step" id="step2">
            <p style="text-align:center;color:#666;margin-bottom:20px;">
                <?php echo direction("Enter the 6-digit code sent to", "أدخل الرمز المكون من 6 أرقام المرسل إلى") ?>
                <strong id="displayPhone"></strong>
            </p>
            <form id="verifyForm">
                <div class="code-inputs">
                    <input type="text" class="code-input" maxlength="1" data-index="0">
                    <input type="text" class="code-input" maxlength="1" data-index="1">
                    <input type="text" class="code-input" maxlength="1" data-index="2">
                    <input type="text" class="code-input" maxlength="1" data-index="3">
                    <input type="text" class="code-input" maxlength="1" data-index="4">
                    <input type="text" class="code-input" maxlength="1" data-index="5">
                </div>
                <div class="resend-timer" id="resendTimer"></div>
                <button type="submit" class="btn btn-primary" id="verifyCodeBtn">
                    <?php echo direction("Verify Code", "تحقق من الرمز") ?>
                </button>
                <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                    <?php echo direction("Change Number", "تغيير الرقم") ?>
                </button>
            </form>
        </div>

        <!-- Step 3: Complete Registration -->
        <div class="verification-step" id="step3">
            <form id="registerForm">
                <div class="form-group">
                    <label class="form-label"><?php echo direction("Full Name", "الاسم الكامل") ?></label>
                    <input type="text" class="form-input" id="fullName" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo direction("Email", "البريد الإلكتروني") ?></label>
                    <input type="email" class="form-input" id="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo direction("Password", "كلمة المرور") ?></label>
                    <input type="password" class="form-input" id="password" required>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo direction("Confirm Password", "تأكيد كلمة المرور") ?></label>
                    <input type="password" class="form-input" id="confirmPassword" required>
                </div>
                <button type="submit" class="btn btn-primary" id="registerBtn">
                    <?php echo direction("Complete Registration", "إكمال التسجيل") ?>
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <?php echo direction("Already have an account?", "لديك حساب بالفعل؟") ?>
            <a href="?v=Login" class="auth-link"><?php echo direction("Login", "تسجيل الدخول") ?></a>
        </div>
    </div>
</div>

<script>
let verificationCode = '';
let phoneWithCode = '';
let resendCountdown = 60;
let resendInterval;

// Phone form submission
document.getElementById('phoneForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const phone = document.getElementById('phoneNumber').value;
    const countryCode = document.getElementById('countryCode').value;
    phoneWithCode = countryCode + phone;
    
    if(phone.length !== 8) {
        showAlert('<?php echo direction("Please enter valid 8-digit phone number", "الرجاء إدخال رقم هاتف صحيح مكون من 8 أرقام") ?>', 'danger');
        return;
    }
    
    sendVerificationCode(phoneWithCode);
});

function sendVerificationCode(phone) {
    document.getElementById('sendCodeBtn').disabled = true;
    
    fetch('loyalty-platform/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'sendVerificationCode',
            phone: phone
        })
    })
    .then(r => r.json())
    .then(data => {
        if(data.ok) {
            document.getElementById('displayPhone').textContent = phone;
            goToStep(2);
            startResendTimer();
        } else {
            showAlert(data.message || '<?php echo direction("Failed to send code", "فشل إرسال الرمز") ?>', 'danger');
        }
        document.getElementById('sendCodeBtn').disabled = false;
    });
}

// Code input handling
const codeInputs = document.querySelectorAll('.code-input');
codeInputs.forEach((input, index) => {
    input.addEventListener('input', function() {
        if(this.value.length === 1 && index < codeInputs.length - 1) {
            codeInputs[index + 1].focus();
        }
    });
    
    input.addEventListener('keydown', function(e) {
        if(e.key === 'Backspace' && this.value === '' && index > 0) {
            codeInputs[index - 1].focus();
        }
    });
});

// Verify code form
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const code = Array.from(codeInputs).map(input => input.value).join('');
    
    if(code.length !== 6) {
        showAlert('<?php echo direction("Please enter complete code", "الرجاء إدخال الرمز كاملاً") ?>', 'danger');
        return;
    }
    
    document.getElementById('verifyCodeBtn').disabled = true;
    
    fetch('loyalty-platform/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'verifyCode',
            phone: phoneWithCode,
            code: code
        })
    })
    .then(r => r.json())
    .then(data => {
        if(data.ok) {
            goToStep(3);
        } else {
            showAlert(data.message || '<?php echo direction("Invalid code", "رمز غير صحيح") ?>', 'danger');
        }
        document.getElementById('verifyCodeBtn').disabled = false;
    });
});

// Registration form
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if(password !== confirmPassword) {
        showAlert('<?php echo direction("Passwords do not match", "كلمات المرور غير متطابقة") ?>', 'danger');
        return;
    }
    
    if(password.length < 6) {
        showAlert('<?php echo direction("Password must be at least 6 characters", "يجب أن تكون كلمة المرور 6 أحرف على الأقل") ?>', 'danger');
        return;
    }
    
    document.getElementById('registerBtn').disabled = true;
    
    fetch('loyalty-platform/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'register',
            phone: phoneWithCode,
            fullName: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            password: password
        })
    })
    .then(r => r.json())
    .then(data => {
        if(data.ok) {
            showAlert('<?php echo direction("Registration successful! Redirecting...", "تم التسجيل بنجاح! جاري التحويل...") ?>', 'success');
            setTimeout(() => window.location.href = '?v=Wallet', 2000);
        } else {
            showAlert(data.message || '<?php echo direction("Registration failed", "فشل التسجيل") ?>', 'danger');
            document.getElementById('registerBtn').disabled = false;
        }
    });
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    
    let strength = 0;
    if(password.length >= 6) strength++;
    if(password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if(password.match(/[0-9]/)) strength++;
    if(password.match(/[^a-zA-Z0-9]/)) strength++;
    
    strengthBar.className = 'password-strength-bar';
    if(strength <= 1) strengthBar.classList.add('strength-weak');
    else if(strength <= 2) strengthBar.classList.add('strength-medium');
    else strengthBar.classList.add('strength-strong');
});

function goToStep(step) {
    document.querySelectorAll('.verification-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
}

function startResendTimer() {
    resendCountdown = 60;
    updateResendTimer();
    resendInterval = setInterval(() => {
        resendCountdown--;
        updateResendTimer();
        if(resendCountdown <= 0) {
            clearInterval(resendInterval);
        }
    }, 1000);
}

function updateResendTimer() {
    const timerDiv = document.getElementById('resendTimer');
    if(resendCountdown > 0) {
        timerDiv.innerHTML = `<?php echo direction("Resend code in", "إعادة إرسال الرمز في") ?> ${resendCountdown}s`;
    } else {
        timerDiv.innerHTML = `<a href="#" onclick="sendVerificationCode(phoneWithCode);startResendTimer();return false;" style="color:#667eea;font-weight:600;"><?php echo direction("Resend Code", "إعادة إرسال الرمز") ?></a>`;
    }
}

function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => alertBox.innerHTML = '', 5000);
}
</script>
