<!-- Merchant Redemptions View -->
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

// Get store info
$storeInfoResult = selectDB("stores", "`id`='$storeId' AND `status`='0' LIMIT 1");
if (!$storeInfoResult || !is_array($storeInfoResult) || count($storeInfoResult) == 0) {
    echo "<div class='alert alert-danger'>Store not found.</div>";
    exit;
}
$store = $storeInfoResult[0];
?>

<style>
.redemptions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.page-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.action-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
}

.tab-btn {
    flex: 1;
    padding: 15px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    cursor: pointer;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s;
}

.tab-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.scan-section {
    background: white;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
}

.scan-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.scan-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.scan-subtitle {
    color: #666;
    margin-bottom: 30px;
}

.code-input-group {
    display: flex;
    gap: 10px;
    max-width: 400px;
    margin: 0 auto;
}

.code-input {
    flex: 1;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 18px;
    text-align: center;
    text-transform: uppercase;
}

.btn-verify {
    padding: 15px 30px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}

.redemption-detail {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-top: 20px;
    display: none;
}

.redemption-detail.show {
    display: block;
}

.reward-info {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.reward-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 15px;
}

.reward-details {
    flex: 1;
}

.reward-name {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.reward-desc {
    color: #666;
    margin-bottom: 15px;
}

.reward-cost {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
}

.customer-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #666;
}

.info-value {
    color: #333;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-complete {
    background: #28a745;
    color: white;
}

.btn-complete:hover {
    background: #218838;
}

.btn-cancel {
    background: #dc3545;
    color: white;
}

.btn-cancel:hover {
    background: #c82333;
}

.history-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
}

.filter-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
}

.redemption-list {
    display: grid;
    gap: 15px;
}

.redemption-card {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 20px;
    transition: all 0.2s;
    cursor: pointer;
}

.redemption-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.redemption-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.redemption-code {
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.redemption-date {
    font-size: 12px;
    color: #999;
}

.redemption-reward {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 5px;
}

.redemption-customer {
    font-size: 14px;
    color: #666;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.3;
}

.empty-text {
    color: #999;
}

@media (max-width: 768px) {
    .reward-info {
        flex-direction: column;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="redemptions-container">
    <!-- Header -->
    <div class="page-header">
        <h1>🎁 <?= direction('Reward Redemptions', 'استرداد المكافآت') ?></h1>
        <p><?= direction($store['enStoreName'], $store['arStoreName']) ?></p>
    </div>

    <!-- Action Tabs -->
    <div class="action-tabs">
        <button class="tab-btn active" onclick="switchTab('verify')">
            🔍 <?= direction('Verify Code', 'التحقق من الكود') ?>
        </button>
        <button class="tab-btn" onclick="switchTab('history')">
            📋 <?= direction('History', 'السجل') ?>
        </button>
    </div>

    <!-- Verify Code Tab -->
    <div class="tab-content active" data-tab="verify">
        <div class="scan-section">
            <div class="scan-icon">🎫</div>
            <div class="scan-title"><?= direction('Verify Redemption Code', 'تحقق من كود الاسترداد') ?></div>
            <div class="scan-subtitle"><?= direction('Enter the customer\'s redemption code', 'أدخل كود الاسترداد الخاص بالعميل') ?></div>
            
            <div class="code-input-group">
                <input type="text" class="code-input" id="redemptionCode" 
                       placeholder="XXXX-XXXX-XXXX" 
                       maxlength="14"
                       onkeyup="formatCode(this)">
                <button class="btn-verify" onclick="verifyCode()">
                    <?= direction('Verify', 'تحقق') ?>
                </button>
            </div>
        </div>

        <!-- Redemption Detail (Hidden by default) -->
        <div class="redemption-detail" id="redemptionDetail">
            <div class="reward-info">
                <img id="rewardImage" class="reward-image" src="" alt="Reward">
                <div class="reward-details">
                    <div class="reward-name" id="rewardName"></div>
                    <div class="reward-desc" id="rewardDesc"></div>
                    <div class="reward-cost" id="rewardCost"></div>
                </div>
            </div>
            
            <div class="customer-info">
                <div class="info-row">
                    <span class="info-label"><?= direction('Customer', 'العميل') ?></span>
                    <span class="info-value" id="customerName"></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?= direction('Redemption Code', 'كود الاسترداد') ?></span>
                    <span class="info-value" id="displayCode"></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?= direction('Redeemed On', 'تاريخ الاسترداد') ?></span>
                    <span class="info-value" id="redemptionDate"></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><?= direction('Status', 'الحالة') ?></span>
                    <span class="info-value">
                        <span class="status-badge status-pending" id="statusBadge">
                            <?= direction('Pending', 'قيد الانتظار') ?>
                        </span>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-complete" onclick="completeRedemption()">
                    ✓ <?= direction('Mark as Fulfilled', 'تحديد كمكتمل') ?>
                </button>
                <button class="btn btn-cancel" onclick="cancelRedemption()">
                    ✕ <?= direction('Cancel', 'إلغاء') ?>
                </button>
            </div>
        </div>
    </div>

    <!-- History Tab -->
    <div class="tab-content" data-tab="history">
        <div class="history-section">
            <div class="filter-row">
                <select class="filter-select" onchange="filterRedemptions(this.value)">
                    <option value="all"><?= direction('All Status', 'كل الحالات') ?></option>
                    <option value="pending"><?= direction('Pending', 'قيد الانتظار') ?></option>
                    <option value="completed"><?= direction('Completed', 'مكتمل') ?></option>
                    <option value="cancelled"><?= direction('Cancelled', 'ملغى') ?></option>
                </select>
                
                <select class="filter-select" onchange="filterRedemptions()">
                    <option value="today"><?= direction('Today', 'اليوم') ?></option>
                    <option value="week"><?= direction('This Week', 'هذا الأسبوع') ?></option>
                    <option value="month"><?= direction('This Month', 'هذا الشهر') ?></option>
                    <option value="all"><?= direction('All Time', 'كل الأوقات') ?></option>
                </select>
            </div>
            
            <div class="redemption-list" id="redemptionList">
                <!-- Will be populated dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
const storeId = <?= $storeId ?>;
let currentRedemptionId = null;

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
    
    if (tab === 'history') {
        loadRedemptionHistory();
    }
}

function formatCode(input) {
    let value = input.value.replace(/[^A-Z0-9]/g, '');
    let formatted = '';
    for (let i = 0; i < value.length && i < 12; i++) {
        if (i > 0 && i % 4 === 0) {
            formatted += '-';
        }
        formatted += value[i];
    }
    input.value = formatted;
}

function verifyCode() {
    const code = document.getElementById('redemptionCode').value.replace(/-/g, '');
    
    if (code.length < 12) {
        alert('<?= direction('Please enter a valid code', 'الرجاء إدخال كود صالح') ?>');
        return;
    }
    
    fetch(`loyalty-platform/api/rewards.php?action=checkRedemption&code=${code}&storeId=${storeId}`)
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                displayRedemption(data.data);
            } else {
                alert(data.msg || '<?= direction('Invalid or expired code', 'كود غير صالح أو منتهي الصلاحية') ?>');
            }
        });
}

function displayRedemption(redemption) {
    currentRedemptionId = redemption.id;
    
    document.getElementById('rewardImage').src = redemption.rewardImage || 'img/placeholder.jpg';
    document.getElementById('rewardName').textContent = redemption.rewardName;
    document.getElementById('rewardDesc').textContent = redemption.rewardDescription || '';
    document.getElementById('rewardCost').textContent = redemption.pointsCost + ' <?= direction('points', 'نقطة') ?>';
    
    document.getElementById('customerName').textContent = redemption.customerName;
    document.getElementById('displayCode').textContent = redemption.redemptionCode;
    document.getElementById('redemptionDate').textContent = new Date(redemption.date).toLocaleString();
    
    const statusBadge = document.getElementById('statusBadge');
    statusBadge.className = 'status-badge status-' + redemption.status;
    statusBadge.textContent = redemption.status.charAt(0).toUpperCase() + redemption.status.slice(1);
    
    document.getElementById('redemptionDetail').classList.add('show');
}

function completeRedemption() {
    if (!confirm('<?= direction('Mark this redemption as fulfilled?', 'تحديد هذا الاسترداد كمكتمل؟') ?>')) {
        return;
    }
    
    fetch('loyalty-platform/api/rewards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'completeRedemption',
            redemptionId: currentRedemptionId,
            employeeId: <?= $employeeId ?>
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Redemption completed successfully', 'تم إكمال الاسترداد بنجاح') ?>');
            document.getElementById('redemptionDetail').classList.remove('show');
            document.getElementById('redemptionCode').value = '';
            currentRedemptionId = null;
        } else {
            alert(data.msg || '<?= direction('Failed to complete', 'فشل الإكمال') ?>');
        }
    });
}

function cancelRedemption() {
    if (!confirm('<?= direction('Cancel this redemption? Points will be refunded.', 'إلغاء هذا الاسترداد؟ سيتم استرداد النقاط.') ?>')) {
        return;
    }
    
    fetch('loyalty-platform/api/rewards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'cancelRedemption',
            redemptionId: currentRedemptionId,
            employeeId: <?= $employeeId ?>
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Redemption cancelled, points refunded', 'تم إلغاء الاسترداد واسترداد النقاط') ?>');
            document.getElementById('redemptionDetail').classList.remove('show');
            document.getElementById('redemptionCode').value = '';
            currentRedemptionId = null;
        } else {
            alert(data.msg || '<?= direction('Failed to cancel', 'فشل الإلغاء') ?>');
        }
    });
}

function loadRedemptionHistory() {
    fetch(`loyalty-platform/api/rewards.php?action=getRedemptions&storeId=${storeId}&limit=50`)
        .then(r => r.json())
        .then(data => {
            if (data.ok && data.data.length > 0) {
                displayRedemptionList(data.data);
            } else {
                displayEmptyState();
            }
        });
}

function displayRedemptionList(redemptions) {
    const listDiv = document.getElementById('redemptionList');
    listDiv.innerHTML = redemptions.map(r => {
        const date = new Date(r.date).toLocaleDateString();
        const statusClass = 'status-' + r.status;
        return `
            <div class="redemption-card" onclick="showRedemptionDetails(${r.id})">
                <div class="redemption-header">
                    <div>
                        <div class="redemption-code">${r.redemptionCode}</div>
                        <div class="redemption-date">${date}</div>
                    </div>
                    <span class="status-badge ${statusClass}">${r.status}</span>
                </div>
                <div class="redemption-reward">${r.rewardName}</div>
                <div class="redemption-customer">${r.customerName}</div>
            </div>
        `;
    }).join('');
}

function displayEmptyState() {
    document.getElementById('redemptionList').innerHTML = `
        <div class="empty-state">
            <div class="empty-icon">🎁</div>
            <div class="empty-text"><?= direction('No redemptions found', 'لم يتم العثور على استردادات') ?></div>
        </div>
    `;
}

function filterRedemptions(status) {
    // Filter logic would be implemented here
    loadRedemptionHistory();
}
</script>
