<!-- Client Login View -->
<style>
.auth-container {
    max-width: 450px;
    margin: 80px auto;
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

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.forgot-link {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.divider {
    text-align: center;
    margin: 25px 0;
    position: relative;
}

.divider::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: #e9ecef;
}

.divider span {
    background: white;
    padding: 0 15px;
    position: relative;
    color: #999;
    font-size: 14px;
}

.social-login {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.social-btn {
    padding: 12px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.social-btn:hover {
    border-color: #667eea;
    transform: translateY(-2px);
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

@media (max-width: 768px) {
    .auth-container {
        margin: 20px auto;
    }
}
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">👋</div>
            <h1 class="auth-title"><?php echo direction("Welcome Back", "مرحباً بعودتك") ?></h1>
            <p class="auth-subtitle"><?php echo direction("Login to access your loyalty wallet", "تسجيل الدخول للوصول إلى محفظة الولاء") ?></p>
        </div>

        <div id="alertBox"></div>

        <form id="loginForm">
            <div class="form-group">
                <label class="form-label"><?php echo direction("Phone or Email", "الهاتف أو البريد الإلكتروني") ?></label>
                <input type="text" class="form-input" id="identifier" 
                       placeholder="<?php echo direction('Enter phone or email', 'أدخل الهاتف أو البريد الإلكتروني') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo direction("Password", "كلمة المرور") ?></label>
                <input type="password" class="form-input" id="password" 
                       placeholder="<?php echo direction('Enter password', 'أدخل كلمة المرور') ?>" required>
            </div>

            <div class="form-options">
                <div class="checkbox-group">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe" style="font-size:14px;color:#666;cursor:pointer;">
                        <?php echo direction("Remember me", "تذكرني") ?>
                    </label>
                </div>
                <a href="?v=ForgetPassword" class="forgot-link">
                    <?php echo direction("Forgot Password?", "نسيت كلمة المرور؟") ?>
                </a>
            </div>

            <button type="submit" class="btn btn-primary" id="loginBtn">
                <?php echo direction("Login", "تسجيل الدخول") ?>
            </button>
        </form>

        <div class="divider">
            <span><?php echo direction("OR", "أو") ?></span>
        </div>

        <div class="social-login">
            <button class="social-btn" onclick="alert('Google login coming soon')">
                <span>📱</span> Google
            </button>
            <button class="social-btn" onclick="alert('Apple login coming soon')">
                <span>🍎</span> Apple
            </button>
        </div>

        <div class="auth-footer">
            <?php echo direction("Don't have an account?", "ليس لديك حساب؟") ?>
            <a href="?v=Register" class="auth-link"><?php echo direction("Register", "التسجيل") ?></a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const identifier = document.getElementById('identifier').value;
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;
    
    if(!identifier || !password) {
        showAlert('<?php echo direction("Please fill all fields", "الرجاء ملء جميع الحقول") ?>', 'danger');
        return;
    }
    
    document.getElementById('loginBtn').disabled = true;
    document.getElementById('loginBtn').textContent = '<?php echo direction("Logging in...", "جاري تسجيل الدخول...") ?>';
    
    fetch('loyalty-platform/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'login',
            identifier: identifier,
            password: password,
            rememberMe: rememberMe
        })
    })
    .then(r => r.json())
    .then(data => {
        if(data.ok) {
            showAlert('<?php echo direction("Login successful! Redirecting...", "تم تسجيل الدخول بنجاح! جاري التحويل...") ?>', 'success');
            setTimeout(() => {
                window.location.href = '?v=Wallet';
            }, 1000);
        } else {
            showAlert(data.message || '<?php echo direction("Invalid credentials", "بيانات غير صحيحة") ?>', 'danger');
            document.getElementById('loginBtn').disabled = false;
            document.getElementById('loginBtn').textContent = '<?php echo direction("Login", "تسجيل الدخول") ?>';
        }
    })
    .catch(err => {
        showAlert('<?php echo direction("Connection error", "خطأ في الاتصال") ?>', 'danger');
        document.getElementById('loginBtn').disabled = false;
        document.getElementById('loginBtn').textContent = '<?php echo direction("Login", "تسجيل الدخول") ?>';
    });
});

function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => alertBox.innerHTML = '', 5000);
}
</script>
