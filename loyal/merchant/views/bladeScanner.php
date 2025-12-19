<!-- Merchant QR Scanner View -->
<?php
if (!isset($_SESSION['employeeId']) || empty($_SESSION['employeeId'])) {
    header("Location: login.php");
    exit;
}

// Get merchant store info
$employeeId = $_SESSION['employeeId'];
$storeResult = selectDB("store_staff", "`employeeId`='$employeeId' AND `status`='0' LIMIT 1");
if (!$storeResult || $storeResult === 0 || !is_array($storeResult) || count($storeResult) == 0) {
    echo "<div class='alert alert-danger'>You are not assigned to any store.</div>";
    exit;
}
$staffData = $storeResult[0];
$storeId = $staffData['storeId'];

$storeInfoResult = selectDB("stores", "`id`='$storeId' AND `status`='0' LIMIT 1");
if (!$storeInfoResult || $storeInfoResult === 0 || !is_array($storeInfoResult) || count($storeInfoResult) == 0) {
    echo "<div class='alert alert-danger'>Store not found.</div>";
    exit;
}
$storeInfo = $storeInfoResult[0];
?>

<style>
.scanner-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.scanner-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.scanner-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.scan-modes {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 30px;
}

.mode-btn {
    padding: 20px;
    background: white;
    border: 3px solid #e9ecef;
    border-radius: 15px;
    cursor: pointer;
    text-align: center;
    transition: all 0.2s;
}

.mode-btn.active {
    border-color: #667eea;
    background: #f8f9fe;
}

.mode-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.mode-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.mode-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.mode-desc {
    font-size: 14px;
    color: #666;
}

.scanner-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

#qr-reader {
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
}

#qr-reader video {
    border-radius: 15px;
}

.scanner-controls {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.control-btn {
    padding: 12px 24px;
    border: none;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-start {
    background: #28a745;
    color: white;
}

.btn-stop {
    background: #dc3545;
    color: white;
}

.btn-switch {
    background: #6c757d;
    color: white;
}

.control-btn:hover {
    transform: scale(1.05);
}

.scan-result {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    margin-top: 20px;
    display: none;
}

.customer-card-preview {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.customer-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
}

.customer-name {
    font-size: 20px;
    font-weight: 700;
    color: #333;
}

.customer-phone {
    font-size: 14px;
    color: #666;
}

.balance-display {
    text-align: right;
}

.balance-value {
    font-size: 32px;
    font-weight: 700;
    color: #667eea;
}

.balance-label {
    font-size: 14px;
    color: #666;
}

.action-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 20px;
}

.action-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.action-card:hover {
    background: #e9ecef;
    transform: translateY(-3px);
}

.action-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.action-title {
    font-size: 16px;
    font-weight: 700;
    color: #333;
}

.transaction-form {
    display: none;
    margin-top: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
}

.submit-btn {
    width: 100%;
    padding: 15px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover {
    background: #5568d3;
    transform: scale(1.02);
}

.recent-scans {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.scan-history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.scan-history-item:last-child {
    border-bottom: none;
}

.success-animation {
    text-align: center;
    padding: 40px;
}

.success-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .scan-modes {
        grid-template-columns: 1fr;
    }
    
    .action-section {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="scanner-container">
    <!-- Header -->
    <div class="scanner-header">
        <h1>📱 <?= direction('QR Scanner', 'ماسح QR') ?></h1>
        <p><?= direction($storeInfo['enStoreName'], $storeInfo['arStoreName']) ?></p>
    </div>

    <!-- Scan Modes -->
    <div class="scan-modes">
        <div class="mode-btn active" onclick="selectMode('scan')">
            <div class="mode-icon">📷</div>
            <div class="mode-title"><?= direction('Scan QR', 'مسح QR') ?></div>
            <div class="mode-desc"><?= direction('Scan customer card', 'مسح بطاقة العميل') ?></div>
        </div>
        <div class="mode-btn" onclick="selectMode('manual')">
            <div class="mode-icon">⌨️</div>
            <div class="mode-title"><?= direction('Manual Entry', 'إدخال يدوي') ?></div>
            <div class="mode-desc"><?= direction('Enter card number', 'إدخال رقم البطاقة') ?></div>
        </div>
    </div>

    <!-- Scanner Section -->
    <div class="scanner-section" id="scannerSection">
        <div id="qr-reader" style="width: 100%;"></div>
        <div class="scanner-controls">
            <button class="control-btn btn-start" id="startBtn" onclick="startScanner()">
                ▶ <?= direction('Start Scanner', 'بدء المسح') ?>
            </button>
            <button class="control-btn btn-stop" id="stopBtn" onclick="stopScanner()" style="display:none;">
                ⏹ <?= direction('Stop Scanner', 'إيقاف المسح') ?>
            </button>
            <button class="control-btn btn-switch" id="switchBtn" onclick="switchCamera()" style="display:none;">
                🔄 <?= direction('Switch Camera', 'تبديل الكاميرا') ?>
            </button>
        </div>
    </div>

    <!-- Manual Entry Section -->
    <div class="scanner-section" id="manualSection" style="display:none;">
        <h3><?= direction('Enter Card Number', 'إدخال رقم البطاقة') ?></h3>
        <div class="form-group">
            <input type="text" id="manualCardNumber" placeholder="<?= direction('Card Number (e.g., LOYAL-XXXX-XXXX)', 'رقم البطاقة') ?>" 
                   maxlength="19" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">
        </div>
        <button class="submit-btn" onclick="lookupManualCard()">
            🔍 <?= direction('Lookup Card', 'البحث عن البطاقة') ?>
        </button>
    </div>

    <!-- Customer Card Preview (Hidden until scan) -->
    <div id="cardPreview" style="display:none;">
        <div class="customer-card-preview">
            <div class="card-header">
                <div class="customer-info">
                    <div class="customer-avatar" id="customerAvatar"></div>
                    <div>
                        <div class="customer-name" id="customerName"></div>
                        <div class="customer-phone" id="customerPhone"></div>
                    </div>
                </div>
                <div class="balance-display">
                    <div class="balance-value" id="balanceValue">0</div>
                    <div class="balance-label" id="balanceLabel"><?= direction('Points', 'نقطة') ?></div>
                </div>
            </div>

            <div class="action-section">
                <div class="action-card" onclick="showTransactionForm('earn')">
                    <div class="action-icon">➕</div>
                    <div class="action-title"><?= direction('Add Points', 'إضافة نقاط') ?></div>
                </div>
                <div class="action-card" onclick="showTransactionForm('redeem')">
                    <div class="action-icon">🎁</div>
                    <div class="action-title"><?= direction('Redeem Reward', 'استبدال مكافأة') ?></div>
                </div>
            </div>

            <!-- Transaction Forms -->
            <div id="earnForm" class="transaction-form">
                <h4><?= direction('Add Points/Stamps', 'إضافة نقاط/أختام') ?></h4>
                <div class="form-group">
                    <label><?= direction('Purchase Amount (SAR)', 'مبلغ الشراء (ريال)') ?></label>
                    <input type="number" id="purchaseAmount" min="0" step="0.01" 
                           placeholder="0.00" onchange="calculatePoints()">
                </div>
                <div class="form-group">
                    <label><?= direction('Points to Add', 'النقاط المضافة') ?></label>
                    <input type="number" id="pointsToAdd" readonly style="background: #f0f0f0;">
                </div>
                <div class="form-group">
                    <label><?= direction('Receipt Number (Optional)', 'رقم الفاتورة (اختياري)') ?></label>
                    <input type="text" id="receiptNumber" placeholder="REC-12345">
                </div>
                <div class="form-group">
                    <label><?= direction('Notes', 'ملاحظات') ?></label>
                    <textarea id="transactionNotes" rows="3" placeholder="<?= direction('Additional notes...', 'ملاحظات إضافية...') ?>"></textarea>
                </div>
                <button class="submit-btn" onclick="processTransaction('earn')">
                    ✓ <?= direction('Confirm Transaction', 'تأكيد العملية') ?>
                </button>
            </div>

            <div id="redeemForm" class="transaction-form">
                <h4><?= direction('Redeem Reward', 'استبدال مكافأة') ?></h4>
                <div class="form-group">
                    <label><?= direction('Redemption Code', 'رمز الاستبدال') ?></label>
                    <input type="text" id="redemptionCode" placeholder="RED-XXXXXX" 
                           style="font-family: 'Courier New', monospace; letter-spacing: 2px;">
                </div>
                <button class="submit-btn" onclick="processRedemption()">
                    ✓ <?= direction('Confirm Redemption', 'تأكيد الاستبدال') ?>
                </button>
            </div>

            <button class="control-btn btn-start" onclick="resetScanner()" style="margin-top: 20px; width: 100%;">
                🔄 <?= direction('Scan Another Card', 'مسح بطاقة أخرى') ?>
            </button>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="recent-scans">
        <h3><?= direction('Recent Scans', 'المسح الأخير') ?></h3>
        <div id="scanHistory"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let currentCamera = 'environment';
let currentCardData = null;
const storeId = <?= $storeId ?>;

function selectMode(mode) {
    document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.mode-btn').classList.add('active');
    
    if (mode === 'scan') {
        document.getElementById('scannerSection').style.display = 'block';
        document.getElementById('manualSection').style.display = 'none';
    } else {
        document.getElementById('scannerSection').style.display = 'none';
        document.getElementById('manualSection').style.display = 'block';
        stopScanner();
    }
}

function startScanner() {
    html5QrCode = new Html5Qrcode("qr-reader");
    
    html5QrCode.start(
        { facingMode: currentCamera },
        { fps: 10, qrbox: 250 },
        onScanSuccess,
        onScanError
    ).then(() => {
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('stopBtn').style.display = 'inline-block';
        document.getElementById('switchBtn').style.display = 'inline-block';
    }).catch(err => {
        alert('<?= direction('Camera access denied', 'تم رفض الوصول للكاميرا') ?>');
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            document.getElementById('startBtn').style.display = 'inline-block';
            document.getElementById('stopBtn').style.display = 'none';
            document.getElementById('switchBtn').style.display = 'none';
        });
    }
}

function switchCamera() {
    currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
    stopScanner();
    setTimeout(startScanner, 500);
}

function onScanSuccess(decodedText, decodedResult) {
    stopScanner();
    processQRCode(decodedText);
}

function onScanError(errorMessage) {
    // Ignore scan errors (too noisy)
}

function processQRCode(qrData) {
    fetch('loyalty-platform/api/cards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'scanQR',
            qrData: qrData,
            storeId: storeId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            displayCustomerCard(data.data);
            addToHistory(data.data);
        } else {
            alert(data.msg || '<?= direction('Invalid QR code', 'رمز QR غير صالح') ?>');
        }
    });
}

function lookupManualCard() {
    const cardNumber = document.getElementById('manualCardNumber').value.trim();
    if (!cardNumber) {
        alert('<?= direction('Please enter card number', 'الرجاء إدخال رقم البطاقة') ?>');
        return;
    }

    fetch(`loyalty-platform/api/cards.php?action=lookup&cardNumber=${encodeURIComponent(cardNumber)}&storeId=${storeId}`)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            displayCustomerCard(data.data);
        } else {
            alert(data.msg || '<?= direction('Card not found', 'البطاقة غير موجودة') ?>');
        }
    });
}

function displayCustomerCard(cardData) {
    currentCardData = cardData;
    
    document.getElementById('customerAvatar').innerText = cardData.customerName.charAt(0).toUpperCase();
    document.getElementById('customerName').innerText = cardData.customerName;
    document.getElementById('customerPhone').innerText = cardData.customerPhone || '';
    document.getElementById('balanceValue').innerText = cardData.currentPoints || cardData.currentStamps || 0;
    document.getElementById('balanceLabel').innerText = cardData.programType === 'stamps' ? 
        '<?= direction('Stamps', 'ختم') ?>' : '<?= direction('Points', 'نقطة') ?>';
    
    document.getElementById('cardPreview').style.display = 'block';
}

function showTransactionForm(type) {
    document.getElementById('earnForm').style.display = type === 'earn' ? 'block' : 'none';
    document.getElementById('redeemForm').style.display = type === 'redeem' ? 'block' : 'none';
}

function calculatePoints() {
    const amount = parseFloat(document.getElementById('purchaseAmount').value) || 0;
    const conversionRate = parseFloat(currentCardData.pointsPerSAR) || 1;
    const points = Math.floor(amount * conversionRate);
    document.getElementById('pointsToAdd').value = points;
}

function processTransaction(type) {
    const amount = document.getElementById('purchaseAmount').value;
    const points = document.getElementById('pointsToAdd').value;
    const receipt = document.getElementById('receiptNumber').value;
    const notes = document.getElementById('transactionNotes').value;

    if (!amount || amount <= 0) {
        alert('<?= direction('Please enter purchase amount', 'الرجاء إدخال مبلغ الشراء') ?>');
        return;
    }

    fetch('loyalty-platform/api/cards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'processTransaction',
            cardId: currentCardData.cardId,
            type: type,
            amount: amount,
            points: points,
            receiptNumber: receipt,
            notes: notes,
            storeId: storeId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showSuccess('<?= direction('Transaction completed!', 'تمت العملية بنجاح!') ?>');
            setTimeout(() => resetScanner(), 2000);
        } else {
            alert(data.msg || '<?= direction('Transaction failed', 'فشلت العملية') ?>');
        }
    });
}

function processRedemption() {
    const code = document.getElementById('redemptionCode').value.trim();
    if (!code) {
        alert('<?= direction('Please enter redemption code', 'الرجاء إدخال رمز الاستبدال') ?>');
        return;
    }

    fetch('loyalty-platform/api/rewards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'processRedemption',
            redemptionCode: code,
            storeId: storeId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showSuccess('<?= direction('Reward redeemed successfully!', 'تم استبدال المكافأة بنجاح!') ?>');
            setTimeout(() => resetScanner(), 2000);
        } else {
            alert(data.msg || '<?= direction('Invalid redemption code', 'رمز استبدال غير صالح') ?>');
        }
    });
}

function showSuccess(message) {
    document.getElementById('cardPreview').innerHTML = `
        <div class="success-animation">
            <div class="success-icon">✓</div>
            <h2>${message}</h2>
        </div>
    `;
}

function resetScanner() {
    document.getElementById('cardPreview').style.display = 'none';
    document.getElementById('earnForm').style.display = 'none';
    document.getElementById('redeemForm').style.display = 'none';
    document.getElementById('purchaseAmount').value = '';
    document.getElementById('pointsToAdd').value = '';
    document.getElementById('receiptNumber').value = '';
    document.getElementById('transactionNotes').value = '';
    currentCardData = null;
}

function addToHistory(cardData) {
    const historyHtml = `
        <div class="scan-history-item">
            <div>
                <strong>${cardData.customerName}</strong><br>
                <small>${cardData.cardNumber}</small>
            </div>
            <div style="text-align: right;">
                <strong>${cardData.currentPoints || cardData.currentStamps}</strong><br>
                <small>${new Date().toLocaleTimeString()}</small>
            </div>
        </div>
    `;
    document.getElementById('scanHistory').insertAdjacentHTML('afterbegin', historyHtml);
}
</script>
