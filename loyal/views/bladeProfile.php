<!-- User Profile View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$userId = $_SESSION['userId'];

// Get user details
$userResult = selectDB("users", "`id`='$userId' AND `status`='0' LIMIT 1");
$user = ($userResult && is_array($userResult) && count($userResult) > 0) ? $userResult[0] : null;

if (!$user) {
    echo "<div class='alert alert-danger'>User not found</div>";
    exit;
}

// Get user statistics
$cardsResult = selectDB("customer_cards", "`userId`='$userId' AND `status`='0'");
$totalCards = ($cardsResult && is_array($cardsResult)) ? count($cardsResult) : 0;

$redemptionsResult = selectDB("redemptions r 
                              JOIN customer_cards cc ON r.cardId = cc.id",
                              "cc.userId = '$userId' AND r.status = 'completed'");
$totalRedemptions = ($redemptionsResult && is_array($redemptionsResult)) ? count($redemptionsResult) : 0;

// Calculate total points across all cards
$totalPoints = 0;
if ($cardsResult && is_array($cardsResult)) {
    foreach ($cardsResult as $card) {
        $totalPoints += $card['currentPoints'] ?? 0;
    }
}
?>

<style>
.profile-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
    min-height: 100vh;
}

.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 20px;
    text-align: center;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 700;
    margin: 0 auto 20px;
    border: 4px solid rgba(255,255,255,0.3);
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 25px;
}

.stat-item {
    background: rgba(255,255,255,0.2);
    padding: 15px;
    border-radius: 10px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    opacity: 0.9;
}

.section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-icon {
    font-size: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.info-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
    font-weight: 600;
}

.info-value {
    font-size: 16px;
    color: #333;
    font-weight: 500;
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
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
}

.form-input:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

.btn-primary {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #5568d3;
    transform: scale(1.05);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #c82333;
}

.button-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-label {
    flex: 1;
}

.setting-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.setting-desc {
    font-size: 12px;
    color: #666;
}

.toggle-switch {
    position: relative;
    width: 50px;
    height: 26px;
    background: #ccc;
    border-radius: 20px;
    cursor: pointer;
    transition: background 0.2s;
}

.toggle-switch.active {
    background: #667eea;
}

.toggle-slider {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: transform 0.2s;
}

.toggle-switch.active .toggle-slider {
    transform: translateX(24px);
}

.language-selector {
    display: flex;
    gap: 10px;
}

.lang-btn {
    padding: 10px 20px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.lang-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.danger-zone {
    background: #fff5f5;
    border: 2px solid #fee;
    padding: 20px;
    border-radius: 10px;
}

.danger-title {
    color: #dc3545;
    font-weight: 700;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .button-group {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary, .btn-danger {
        width: 100%;
    }
}
</style>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <?= strtoupper(substr($user['firstName'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="profile-name">
            <?= htmlspecialchars(($user['firstName'] ?? '') . ' ' . ($user['lastName'] ?? '')) ?>
        </div>
        <p><?= htmlspecialchars($user['email'] ?? $user['phone'] ?? '') ?></p>
        
        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-value"><?= $totalCards ?></div>
                <div class="stat-label"><?= direction('Cards', 'بطاقة') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= number_format($totalPoints) ?></div>
                <div class="stat-label"><?= direction('Total Points', 'إجمالي النقاط') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $totalRedemptions ?></div>
                <div class="stat-label"><?= direction('Rewards', 'مكافأة') ?></div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">
            <span class="section-icon">👤</span>
            <?= direction('Personal Information', 'المعلومات الشخصية') ?>
        </div>
        
        <form id="profileForm">
            <div class="info-grid">
                <div class="form-group">
                    <label class="form-label"><?= direction('First Name', 'الاسم الأول') ?></label>
                    <input type="text" class="form-input" id="firstName" 
                           value="<?= htmlspecialchars($user['firstName'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Last Name', 'اسم العائلة') ?></label>
                    <input type="text" class="form-input" id="lastName" 
                           value="<?= htmlspecialchars($user['lastName'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Email', 'البريد الإلكتروني') ?></label>
                    <input type="email" class="form-input" id="email" 
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Phone', 'الهاتف') ?></label>
                    <input type="tel" class="form-input" id="phone" 
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>
            </div>
            
            <div class="button-group">
                <button type="button" class="btn-primary" onclick="saveProfile()">
                    ✓ <?= direction('Save Changes', 'حفظ التغييرات') ?>
                </button>
                <button type="button" class="btn-secondary" onclick="cancelEdit()">
                    <?= direction('Cancel', 'إلغاء') ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Notification Settings -->
    <div class="section">
        <div class="section-title">
            <span class="section-icon">🔔</span>
            <?= direction('Notification Preferences', 'تفضيلات الإشعارات') ?>
        </div>
        
        <div class="setting-item">
            <div class="setting-label">
                <div class="setting-title"><?= direction('Points Earned', 'نقاط مكتسبة') ?></div>
                <div class="setting-desc"><?= direction('Notify when you earn points', 'إشعار عند كسب النقاط') ?></div>
            </div>
            <div class="toggle-switch active" onclick="toggleSetting(this, 'points_earned')">
                <div class="toggle-slider"></div>
            </div>
        </div>
        
        <div class="setting-item">
            <div class="setting-label">
                <div class="setting-title"><?= direction('Rewards Available', 'مكافآت متاحة') ?></div>
                <div class="setting-desc"><?= direction('Notify when you can redeem rewards', 'إشعار عند إمكانية استبدال المكافآت') ?></div>
            </div>
            <div class="toggle-switch active" onclick="toggleSetting(this, 'rewards_available')">
                <div class="toggle-slider"></div>
            </div>
        </div>
        
        <div class="setting-item">
            <div class="setting-label">
                <div class="setting-title"><?= direction('Points Expiring', 'نقاط على وشك الانتهاء') ?></div>
                <div class="setting-desc"><?= direction('Notify before points expire', 'إشعار قبل انتهاء صلاحية النقاط') ?></div>
            </div>
            <div class="toggle-switch active" onclick="toggleSetting(this, 'points_expiring')">
                <div class="toggle-slider"></div>
            </div>
        </div>
        
        <div class="setting-item">
            <div class="setting-label">
                <div class="setting-title"><?= direction('Promotions', 'العروض الترويجية') ?></div>
                <div class="setting-desc"><?= direction('Receive promotional offers', 'استلام العروض الترويجية') ?></div>
            </div>
            <div class="toggle-switch" onclick="toggleSetting(this, 'promotions')">
                <div class="toggle-slider"></div>
            </div>
        </div>
    </div>

    <!-- Language & Region -->
    <div class="section">
        <div class="section-title">
            <span class="section-icon">🌐</span>
            <?= direction('Language & Region', 'اللغة والمنطقة') ?>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= direction('Preferred Language', 'اللغة المفضلة') ?></label>
            <div class="language-selector">
                <button class="lang-btn active" onclick="changeLanguage('en')">
                    English
                </button>
                <button class="lang-btn" onclick="changeLanguage('ar')">
                    العربية
                </button>
            </div>
        </div>
    </div>

    <!-- Security -->
    <div class="section">
        <div class="section-title">
            <span class="section-icon">🔒</span>
            <?= direction('Security', 'الأمان') ?>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= direction('Current Password', 'كلمة المرور الحالية') ?></label>
            <input type="password" class="form-input" id="currentPassword" placeholder="••••••••">
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= direction('New Password', 'كلمة المرور الجديدة') ?></label>
            <input type="password" class="form-input" id="newPassword" placeholder="••••••••">
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= direction('Confirm Password', 'تأكيد كلمة المرور') ?></label>
            <input type="password" class="form-input" id="confirmPassword" placeholder="••••••••">
        </div>
        
        <button type="button" class="btn-primary" onclick="changePassword()">
            🔑 <?= direction('Change Password', 'تغيير كلمة المرور') ?>
        </button>
    </div>

    <!-- Danger Zone -->
    <div class="section">
        <div class="section-title">
            <span class="section-icon">⚠️</span>
            <?= direction('Danger Zone', 'منطقة الخطر') ?>
        </div>
        
        <div class="danger-zone">
            <div class="danger-title"><?= direction('Delete Account', 'حذف الحساب') ?></div>
            <p><?= direction('Once you delete your account, there is no going back. All your cards, points, and rewards will be permanently deleted.', 
                'بمجرد حذف حسابك، لا يمكن التراجع. ستتم إزالة جميع بطاقاتك ونقاطك ومكافآتك بشكل نهائي.') ?></p>
            <button type="button" class="btn-danger" onclick="deleteAccount()">
                🗑️ <?= direction('Delete My Account', 'حذف حسابي') ?>
            </button>
        </div>
    </div>
</div>

<script>
function saveProfile() {
    const data = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value
    };
    
    fetch('loyalty-platform/api/users.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'updateProfile', ...data})
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Profile updated successfully', 'تم تحديث الملف الشخصي بنجاح') ?>');
            location.reload();
        } else {
            alert(data.msg || '<?= direction('Update failed', 'فشل التحديث') ?>');
        }
    });
}

function cancelEdit() {
    location.reload();
}

function toggleSetting(element, setting) {
    element.classList.toggle('active');
    const enabled = element.classList.contains('active');
    
    fetch('loyalty-platform/api/users.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'updateNotificationSettings',
            setting: setting,
            enabled: enabled
        })
    });
}

function changeLanguage(lang) {
    document.querySelectorAll('.lang-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    fetch('loyalty-platform/api/users.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'changeLanguage', language: lang})
    })
    .then(() => location.reload());
}

function changePassword() {
    const current = document.getElementById('currentPassword').value;
    const newPass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    
    if (!current || !newPass || !confirm) {
        alert('<?= direction('Please fill all password fields', 'الرجاء ملء جميع حقول كلمة المرور') ?>');
        return;
    }
    
    if (newPass !== confirm) {
        alert('<?= direction('Passwords do not match', 'كلمات المرور غير متطابقة') ?>');
        return;
    }
    
    fetch('loyalty-platform/api/users.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'changePassword',
            currentPassword: current,
            newPassword: newPass
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Password changed successfully', 'تم تغيير كلمة المرور بنجاح') ?>');
            location.reload();
        } else {
            alert(data.msg || '<?= direction('Password change failed', 'فشل تغيير كلمة المرور') ?>');
        }
    });
}

function deleteAccount() {
    if (!confirm('<?= direction('Are you absolutely sure? This action cannot be undone!', 'هل أنت متأكد تماماً؟ لا يمكن التراجع عن هذا الإجراء!') ?>')) {
        return;
    }
    
    const confirmation = prompt('<?= direction('Type DELETE to confirm', 'اكتب DELETE للتأكيد') ?>');
    if (confirmation !== 'DELETE') {
        return;
    }
    
    fetch('loyalty-platform/api/users.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'deleteAccount'})
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Account deleted successfully', 'تم حذف الحساب بنجاح') ?>');
            window.location.href = '?v=Logout';
        } else {
            alert(data.msg || '<?= direction('Deletion failed', 'فشل الحذف') ?>');
        }
    });
}
</script>
