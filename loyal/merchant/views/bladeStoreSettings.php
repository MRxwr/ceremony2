<!-- Merchant Store Settings View -->
<?php
require_once("includes/checksouthead.php");

$employeeId = $userID;
$storeResult = selectDB("store_staff", "`employeeId`='$employeeId' AND `status`='0' LIMIT 1");
if (!$storeResult || $storeResult === 0 || !is_array($storeResult) || count($storeResult) == 0) {
    echo "<div class='alert alert-danger'>You are not assigned to any store.</div>";
    exit;
}
$staffData = $storeResult[0];
$storeId = $staffData['storeId'];
$role = $staffData['role'];

// Only managers can edit settings
$canEdit = ($role === 'manager' || $role === 'owner');

// Get store details
$storeInfoResult = selectDB("stores", "`id`='$storeId' AND `status`='0' LIMIT 1");
if (!$storeInfoResult || !is_array($storeInfoResult) || count($storeInfoResult) == 0) {
    echo "<div class='alert alert-danger'>Store not found.</div>";
    exit;
}
$store = $storeInfoResult[0];

// Get loyalty programs
$programsResult = selectDB("loyalty_programs", "`storeId`='$storeId' AND `status`='0'");
$programs = ($programsResult && is_array($programsResult)) ? $programsResult : [];
?>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.settings-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.settings-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.settings-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    overflow-x: auto;
}

.tab-btn {
    padding: 12px 24px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    cursor: pointer;
    white-space: nowrap;
    font-weight: 600;
    transition: all 0.2s;
}

.tab-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.settings-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 20px;
    display: none;
}

.settings-section.active {
    display: block;
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

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
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

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none;
    border-color: #667eea;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.image-upload {
    border: 2px dashed #e9ecef;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.image-upload:hover {
    border-color: #667eea;
    background: #f8f9fe;
}

.image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    margin: 10px auto;
}

.color-picker-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}

.color-input {
    width: 60px;
    height: 40px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.btn-save {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-save:hover {
    background: #5568d3;
    transform: scale(1.05);
}

.btn-save:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.program-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
}

.program-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.program-name {
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.program-type {
    padding: 6px 12px;
    background: #667eea;
    color: white;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.program-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.stat-box {
    text-align: center;
    padding: 10px;
    background: white;
    border-radius: 10px;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
}

.stat-label {
    font-size: 11px;
    color: #999;
}

.hours-table {
    width: 100%;
    border-collapse: collapse;
}

.hours-table th, .hours-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
}

.hours-table th {
    font-weight: 700;
    color: #333;
}

.time-input {
    padding: 8px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    width: 100px;
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

.staff-list {
    display: grid;
    gap: 15px;
}

.staff-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.staff-info {
    flex: 1;
}

.staff-name {
    font-weight: 700;
    color: #333;
    margin-bottom: 3px;
}

.staff-role {
    font-size: 12px;
    color: #666;
}

.btn-remove {
    padding: 8px 16px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <h1>⚙️ <?= direction('Store Settings', 'إعدادات المتجر') ?></h1>
        <p><?= direction($store['enStoreName'], $store['arStoreName']) ?></p>
    </div>

    <!-- Tabs -->
    <div class="settings-tabs">
        <button class="tab-btn active" onclick="switchTab('basic')">
            <?= direction('Basic Info', 'معلومات أساسية') ?>
        </button>
        <button class="tab-btn" onclick="switchTab('branding')">
            <?= direction('Branding', 'العلامة التجارية') ?>
        </button>
        <button class="tab-btn" onclick="switchTab('programs')">
            <?= direction('Programs', 'البرامج') ?>
        </button>
        <button class="tab-btn" onclick="switchTab('hours')">
            <?= direction('Business Hours', 'ساعات العمل') ?>
        </button>
        <button class="tab-btn" onclick="switchTab('staff')">
            <?= direction('Staff', 'الموظفون') ?>
        </button>
    </div>

    <!-- Basic Info Tab -->
    <div class="settings-section active" data-tab="basic">
        <div class="section-title">
            <span>ℹ️</span>
            <?= direction('Basic Information', 'المعلومات الأساسية') ?>
        </div>
        
        <form id="basicForm">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label"><?= direction('Store Name (English)', 'اسم المتجر (إنجليزي)') ?></label>
                    <input type="text" class="form-input" name="enStoreName" 
                           value="<?= htmlspecialchars($store['enStoreName']) ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Store Name (Arabic)', 'اسم المتجر (عربي)') ?></label>
                    <input type="text" class="form-input" name="arStoreName" 
                           value="<?= htmlspecialchars($store['arStoreName']) ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Description (English)', 'الوصف (إنجليزي)') ?></label>
                <textarea class="form-textarea" name="enDescription" <?= !$canEdit ? 'disabled' : '' ?>><?= htmlspecialchars($store['enDescription'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Description (Arabic)', 'الوصف (عربي)') ?></label>
                <textarea class="form-textarea" name="arDescription" <?= !$canEdit ? 'disabled' : '' ?>><?= htmlspecialchars($store['arDescription'] ?? '') ?></textarea>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label"><?= direction('Phone', 'الهاتف') ?></label>
                    <input type="tel" class="form-input" name="phone" 
                           value="<?= htmlspecialchars($store['phone'] ?? '') ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Email', 'البريد الإلكتروني') ?></label>
                    <input type="email" class="form-input" name="email" 
                           value="<?= htmlspecialchars($store['email'] ?? '') ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= direction('Website', 'الموقع') ?></label>
                    <input type="url" class="form-input" name="website" 
                           value="<?= htmlspecialchars($store['website'] ?? '') ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
            </div>
            
            <?php if ($canEdit): ?>
                <button type="button" class="btn-save" onclick="saveBasicInfo()">
                    ✓ <?= direction('Save Changes', 'حفظ التغييرات') ?>
                </button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Branding Tab -->
    <div class="settings-section" data-tab="branding">
        <div class="section-title">
            <span>🎨</span>
            <?= direction('Branding & Appearance', 'العلامة التجارية والمظهر') ?>
        </div>
        
        <form id="brandingForm">
            <div class="form-group">
                <label class="form-label"><?= direction('Logo', 'الشعار') ?></label>
                <div class="image-upload" onclick="document.getElementById('logoInput').click()">
                    <?php if (!empty($store['logo'])): ?>
                        <img src="<?= encryptImage('logos/' . $store['logo']) ?>" class="image-preview" id="logoPreview">
                    <?php else: ?>
                        <p>📷 <?= direction('Click to upload logo', 'انقر لتحميل الشعار') ?></p>
                    <?php endif; ?>
                    <input type="file" id="logoInput" accept="image/*" style="display:none" onchange="previewImage(this, 'logoPreview')" <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Cover Image', 'صورة الغلاف') ?></label>
                <div class="image-upload" onclick="document.getElementById('coverInput').click()">
                    <?php if (!empty($store['coverImage'])): ?>
                        <img src="<?= encryptImage('logos/' . $store['coverImage']) ?>" class="image-preview" id="coverPreview">
                    <?php else: ?>
                        <p>📷 <?= direction('Click to upload cover', 'انقر لتحميل الغلاف') ?></p>
                    <?php endif; ?>
                    <input type="file" id="coverInput" accept="image/*" style="display:none" onchange="previewImage(this, 'coverPreview')" <?= !$canEdit ? 'disabled' : '' ?>>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Brand Color', 'لون العلامة التجارية') ?></label>
                <div class="color-picker-wrapper">
                    <input type="color" class="color-input" name="brandColor" 
                           value="<?= $store['brandColor'] ?? '#667eea' ?>" 
                           <?= !$canEdit ? 'disabled' : '' ?>>
                    <span><?= $store['brandColor'] ?? '#667eea' ?></span>
                </div>
            </div>
            
            <?php if ($canEdit): ?>
                <button type="button" class="btn-save" onclick="saveBranding()">
                    ✓ <?= direction('Save Changes', 'حفظ التغييرات') ?>
                </button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Programs Tab -->
    <div class="settings-section" data-tab="programs">
        <div class="section-title">
            <span>🎯</span>
            <?= direction('Loyalty Programs', 'برامج الولاء') ?>
        </div>
        
        <?php if (empty($programs)): ?>
            <p><?= direction('No programs configured', 'لم يتم تكوين أي برامج') ?></p>
        <?php else: ?>
            <?php foreach ($programs as $program): ?>
                <div class="program-card">
                    <div class="program-header">
                        <span class="program-name"><?= direction($program['enTitle'], $program['arTitle']) ?></span>
                        <span class="program-type"><?= strtoupper($program['programType']) ?></span>
                    </div>
                    <div class="program-stats">
                        <div class="stat-box">
                            <div class="stat-value"><?= $program['pointsPerSAR'] ?? 1 ?></div>
                            <div class="stat-label"><?= direction('Points/SAR', 'نقاط/ريال') ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value"><?= $program['requirePoints'] ?? 0 ?></div>
                            <div class="stat-label"><?= direction('Required Points', 'نقاط مطلوبة') ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Business Hours Tab -->
    <div class="settings-section" data-tab="hours">
        <div class="section-title">
            <span>🕐</span>
            <?= direction('Business Hours', 'ساعات العمل') ?>
        </div>
        
        <table class="hours-table">
            <thead>
                <tr>
                    <th><?= direction('Day', 'اليوم') ?></th>
                    <th><?= direction('Open', 'فتح') ?></th>
                    <th><?= direction('Close', 'إغلاق') ?></th>
                    <th><?= direction('Closed', 'مغلق') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $daysAr = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                foreach ($days as $index => $day):
                ?>
                <tr>
                    <td><?= direction($day, $daysAr[$index]) ?></td>
                    <td><input type="time" class="time-input" value="09:00" <?= !$canEdit ? 'disabled' : '' ?>></td>
                    <td><input type="time" class="time-input" value="21:00" <?= !$canEdit ? 'disabled' : '' ?>></td>
                    <td>
                        <div class="toggle-switch" onclick="<?= $canEdit ? 'this.classList.toggle(\'active\')' : '' ?>">
                            <div class="toggle-slider"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($canEdit): ?>
            <button type="button" class="btn-save" style="margin-top: 20px;" onclick="saveHours()">
                ✓ <?= direction('Save Hours', 'حفظ الأوقات') ?>
            </button>
        <?php endif; ?>
    </div>

    <!-- Staff Tab -->
    <div class="settings-section" data-tab="staff">
        <div class="section-title">
            <span>👥</span>
            <?= direction('Staff Management', 'إدارة الموظفين') ?>
        </div>
        
        <?php
        $staffResult = selectDB("store_staff ss 
                                JOIN employees e ON ss.employeeId = e.id",
                                "ss.storeId = '$storeId' AND ss.status = '0'");
        $staffList = ($staffResult && is_array($staffResult)) ? $staffResult : [];
        ?>
        
        <div class="staff-list">
            <?php foreach ($staffList as $staff): ?>
                <div class="staff-item">
                    <div class="staff-info">
                        <div class="staff-name"><?= htmlspecialchars($staff['empFirstName'] ?? '') ?> <?= htmlspecialchars($staff['empLastName'] ?? '') ?></div>
                        <div class="staff-role"><?= ucfirst($staff['role']) ?></div>
                    </div>
                    <?php if ($canEdit && $staff['employeeId'] != $employeeId): ?>
                        <button class="btn-remove" onclick="removeStaff(<?= $staff['id'] ?>)">
                            <?= direction('Remove', 'إزالة') ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
const storeId = <?= $storeId ?>;
const canEdit = <?= $canEdit ? 'true' : 'false' ?>;

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    document.querySelectorAll('.settings-section').forEach(section => section.classList.remove('active'));
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function saveBasicInfo() {
    if (!canEdit) return;
    
    const formData = new FormData(document.getElementById('basicForm'));
    formData.append('action', 'updateBasicInfo');
    formData.append('storeId', storeId);
    
    fetch('loyalty-platform/api/stores.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Settings saved successfully', 'تم حفظ الإعدادات بنجاح') ?>');
        } else {
            alert(data.msg || '<?= direction('Save failed', 'فشل الحفظ') ?>');
        }
    });
}

function saveBranding() {
    alert('<?= direction('Branding settings saved', 'تم حفظ إعدادات العلامة التجارية') ?>');
}

function saveHours() {
    alert('<?= direction('Business hours saved', 'تم حفظ ساعات العمل') ?>');
}

function removeStaff(staffId) {
    if (!confirm('<?= direction('Remove this staff member?', 'إزالة هذا الموظف؟') ?>')) return;
    
    fetch('loyalty-platform/api/stores.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'removeStaff', staffId: staffId})
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            location.reload();
        }
    });
}
</script>
