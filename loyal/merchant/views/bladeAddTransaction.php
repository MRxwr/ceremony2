<!-- Merchant Add Transaction View -->
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

// Get loyalty programs
$programsResult = selectDB("loyalty_programs", "`storeId`='$storeId' AND `status`='0'");
$programs = ($programsResult && is_array($programsResult)) ? $programsResult : [];
?>

<style>
.transaction-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.page-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.page-header p {
    margin: 0;
    opacity: 0.9;
}

.transaction-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.step-indicator {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
}

.step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #999;
    position: relative;
}

.step.active {
    background: #667eea;
    color: white;
}

.step.completed {
    background: #28a745;
    color: white;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    width: 50px;
    height: 3px;
    background: #f0f0f0;
    right: -55px;
    top: 50%;
    transform: translateY(-50%);
}

.step.completed:not(:last-child)::after {
    background: #28a745;
}

.transaction-step {
    display: none;
}

.transaction-step.active {
    display: block;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
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

.form-input, .form-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #667eea;
}

.customer-search {
    position: relative;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    margin-top: 5px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 100;
    display: none;
}

.search-results.show {
    display: block;
}

.customer-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.customer-item:hover {
    background: #f8f9fe;
}

.customer-name {
    font-weight: 600;
    color: #333;
}

.customer-phone {
    font-size: 13px;
    color: #666;
}

.selected-customer {
    display: none;
    padding: 15px;
    background: #f8f9fe;
    border-radius: 10px;
    margin-top: 10px;
}

.selected-customer.show {
    display: block;
}

.customer-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-change {
    padding: 6px 12px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
}

.card-select {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.card-option {
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.card-option:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.card-option.selected {
    border-color: #667eea;
    background: #f8f9fe;
}

.card-number {
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.card-balance {
    font-size: 13px;
    color: #666;
}

.amount-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.amount-input {
    flex: 1;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
}

.currency {
    font-size: 18px;
    font-weight: 600;
    color: #666;
}

.calculated-points {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0;
}

.points-value {
    font-size: 36px;
    font-weight: 700;
    margin: 10px 0;
}

.points-label {
    font-size: 14px;
    opacity: 0.9;
}

.transaction-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-row:last-child {
    border-bottom: none;
    font-weight: 700;
    color: #667eea;
    font-size: 18px;
}

.receipt-upload {
    border: 2px dashed #e9ecef;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
}

.receipt-upload:hover {
    border-color: #667eea;
    background: #f8f9fe;
}

.receipt-preview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 10px;
    margin-top: 15px;
}

.button-group {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 12px 24px;
    border: none;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-back {
    background: #f0f0f0;
    color: #333;
}

.btn-next, .btn-submit {
    background: #667eea;
    color: white;
}

.btn-next:hover, .btn-submit:hover {
    background: #5568d3;
    transform: scale(1.02);
}

.success-message {
    text-align: center;
    padding: 40px;
}

.success-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.success-title {
    font-size: 24px;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 10px;
}

.success-details {
    color: #666;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .card-select {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="transaction-container">
    <!-- Header -->
    <div class="page-header">
        <h1>💳 <?= direction('Add Transaction', 'إضافة معاملة') ?></h1>
        <p><?= direction($store['enStoreName'], $store['arStoreName']) ?></p>
    </div>

    <!-- Transaction Card -->
    <div class="transaction-card">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" data-step="1">1</div>
            <div class="step" data-step="2">2</div>
            <div class="step" data-step="3">3</div>
            <div class="step" data-step="4">4</div>
        </div>

        <!-- Step 1: Customer Selection -->
        <div class="transaction-step active" data-step="1">
            <div class="section-title"><?= direction('Select Customer', 'اختر العميل') ?></div>
            
            <div class="form-group customer-search">
                <label class="form-label"><?= direction('Search by name, phone, or email', 'ابحث بالاسم أو الهاتف أو البريد') ?></label>
                <input type="text" class="form-input" id="customerSearch" 
                       placeholder="<?= direction('Start typing...', 'ابدأ الكتابة...') ?>">
                
                <div class="search-results" id="searchResults"></div>
            </div>
            
            <div class="selected-customer" id="selectedCustomer">
                <div class="customer-info">
                    <div>
                        <div class="customer-name" id="selectedName"></div>
                        <div class="customer-phone" id="selectedPhone"></div>
                    </div>
                    <button class="btn-change" onclick="changeCustomer()">
                        <?= direction('Change', 'تغيير') ?>
                    </button>
                </div>
            </div>
            
            <div class="button-group">
                <button class="btn btn-next" onclick="nextStep()" id="step1Next" disabled>
                    <?= direction('Next', 'التالي') ?> →
                </button>
            </div>
        </div>

        <!-- Step 2: Card Selection -->
        <div class="transaction-step" data-step="2">
            <div class="section-title"><?= direction('Select Loyalty Card', 'اختر بطاقة الولاء') ?></div>
            
            <div class="card-select" id="cardSelect">
                <!-- Cards will be loaded dynamically -->
            </div>
            
            <div class="button-group">
                <button class="btn btn-back" onclick="prevStep()">
                    ← <?= direction('Back', 'رجوع') ?>
                </button>
                <button class="btn btn-next" onclick="nextStep()" id="step2Next" disabled>
                    <?= direction('Next', 'التالي') ?> →
                </button>
            </div>
        </div>

        <!-- Step 3: Transaction Details -->
        <div class="transaction-step" data-step="3">
            <div class="section-title"><?= direction('Transaction Details', 'تفاصيل المعاملة') ?></div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Purchase Amount', 'قيمة المشتريات') ?></label>
                <div class="amount-input-group">
                    <input type="number" class="form-input amount-input" id="amount" 
                           placeholder="0.00" step="0.01" min="0" onchange="calculatePoints()">
                    <span class="currency">SAR</span>
                </div>
            </div>
            
            <div class="calculated-points" id="calculatedPoints" style="display:none;">
                <div class="points-label"><?= direction('Points to be earned', 'النقاط المراد كسبها') ?></div>
                <div class="points-value" id="pointsValue">0</div>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Receipt (Optional)', 'الإيصال (اختياري)') ?></label>
                <div class="receipt-upload" onclick="document.getElementById('receiptInput').click()">
                    <p>📄 <?= direction('Click to upload receipt', 'انقر لتحميل الإيصال') ?></p>
                    <input type="file" id="receiptInput" accept="image/*" style="display:none" onchange="previewReceipt(this)">
                    <img id="receiptPreview" class="receipt-preview" style="display:none">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= direction('Notes (Optional)', 'ملاحظات (اختياري)') ?></label>
                <textarea class="form-input" id="notes" rows="3" 
                          placeholder="<?= direction('Add any notes...', 'أضف أي ملاحظات...') ?>"></textarea>
            </div>
            
            <div class="button-group">
                <button class="btn btn-back" onclick="prevStep()">
                    ← <?= direction('Back', 'رجوع') ?>
                </button>
                <button class="btn btn-next" onclick="nextStep()" id="step3Next" disabled>
                    <?= direction('Review', 'مراجعة') ?> →
                </button>
            </div>
        </div>

        <!-- Step 4: Review & Confirm -->
        <div class="transaction-step" data-step="4">
            <div class="section-title"><?= direction('Review Transaction', 'مراجعة المعاملة') ?></div>
            
            <div class="transaction-summary">
                <div class="summary-row">
                    <span><?= direction('Customer', 'العميل') ?></span>
                    <span id="reviewCustomer"></span>
                </div>
                <div class="summary-row">
                    <span><?= direction('Card', 'البطاقة') ?></span>
                    <span id="reviewCard"></span>
                </div>
                <div class="summary-row">
                    <span><?= direction('Amount', 'المبلغ') ?></span>
                    <span id="reviewAmount"></span>
                </div>
                <div class="summary-row">
                    <span><?= direction('Points Earned', 'النقاط المكتسبة') ?></span>
                    <span id="reviewPoints"></span>
                </div>
            </div>
            
            <div class="button-group">
                <button class="btn btn-back" onclick="prevStep()">
                    ← <?= direction('Back', 'رجوع') ?>
                </button>
                <button class="btn btn-submit" onclick="submitTransaction()">
                    ✓ <?= direction('Confirm Transaction', 'تأكيد المعاملة') ?>
                </button>
            </div>
        </div>

        <!-- Success Step -->
        <div class="transaction-step" data-step="5">
            <div class="success-message">
                <div class="success-icon">✓</div>
                <div class="success-title"><?= direction('Transaction Completed!', 'اكتملت المعاملة!') ?></div>
                <div class="success-details">
                    <span id="successPoints"></span> <?= direction('points added to customer account', 'نقاط تمت إضافتها إلى حساب العميل') ?>
                </div>
                <button class="btn btn-next" onclick="location.reload()">
                    <?= direction('New Transaction', 'معاملة جديدة') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const storeId = <?= $storeId ?>;
let currentStep = 1;
let selectedCustomerId = null;
let selectedCardId = null;
let selectedProgramId = null;
let pointsPerSAR = 1;

// Customer Search
let searchTimeout;
document.getElementById('customerSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('searchResults').classList.remove('show');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`loyalty-platform/api/customers.php?action=search&q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                if (data.ok && data.data.length > 0) {
                    displaySearchResults(data.data);
                }
            });
    }, 300);
});

function displaySearchResults(customers) {
    const resultsDiv = document.getElementById('searchResults');
    resultsDiv.innerHTML = customers.map(c => `
        <div class="customer-item" onclick="selectCustomer(${c.id}, '${c.firstName} ${c.lastName}', '${c.phone}')">
            <div class="customer-name">${c.firstName} ${c.lastName}</div>
            <div class="customer-phone">${c.phone}</div>
        </div>
    `).join('');
    resultsDiv.classList.add('show');
}

function selectCustomer(id, name, phone) {
    selectedCustomerId = id;
    document.getElementById('selectedName').textContent = name;
    document.getElementById('selectedPhone').textContent = phone;
    document.getElementById('selectedCustomer').classList.add('show');
    document.getElementById('searchResults').classList.remove('show');
    document.getElementById('customerSearch').value = name;
    document.getElementById('step1Next').disabled = false;
}

function changeCustomer() {
    selectedCustomerId = null;
    document.getElementById('selectedCustomer').classList.remove('show');
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearch').focus();
    document.getElementById('step1Next').disabled = true;
}

function nextStep() {
    if (currentStep === 1) {
        loadCustomerCards();
    } else if (currentStep === 3) {
        populateReview();
    }
    
    currentStep++;
    updateStepUI();
}

function prevStep() {
    currentStep--;
    updateStepUI();
}

function updateStepUI() {
    document.querySelectorAll('.transaction-step').forEach(step => {
        step.classList.remove('active');
    });
    document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
    
    document.querySelectorAll('.step').forEach((step, index) => {
        if (index + 1 < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (index + 1 === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

function loadCustomerCards() {
    fetch(`loyalty-platform/api/cards.php?action=getByCustomer&customerId=${selectedCustomerId}&storeId=${storeId}`)
        .then(r => r.json())
        .then(data => {
            if (data.ok && data.data.length > 0) {
                displayCards(data.data);
            }
        });
}

function displayCards(cards) {
    const cardSelect = document.getElementById('cardSelect');
    cardSelect.innerHTML = cards.map(card => `
        <div class="card-option" onclick="selectCard(${card.id}, ${card.programId}, ${card.pointsPerSAR || 1})">
            <div class="card-number">${card.cardNumber}</div>
            <div class="card-balance">${card.balance || 0} <?= direction('points', 'نقطة') ?></div>
        </div>
    `).join('');
}

function selectCard(cardId, programId, pps) {
    selectedCardId = cardId;
    selectedProgramId = programId;
    pointsPerSAR = pps;
    
    document.querySelectorAll('.card-option').forEach(opt => opt.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('step2Next').disabled = false;
}

function calculatePoints() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    if (amount > 0) {
        const points = Math.floor(amount * pointsPerSAR);
        document.getElementById('pointsValue').textContent = points;
        document.getElementById('calculatedPoints').style.display = 'block';
        document.getElementById('step3Next').disabled = false;
    } else {
        document.getElementById('calculatedPoints').style.display = 'none';
        document.getElementById('step3Next').disabled = true;
    }
}

function previewReceipt(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('receiptPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function populateReview() {
    const name = document.getElementById('selectedName').textContent;
    const amount = document.getElementById('amount').value;
    const points = document.getElementById('pointsValue').textContent;
    
    document.getElementById('reviewCustomer').textContent = name;
    document.getElementById('reviewCard').textContent = selectedCardId;
    document.getElementById('reviewAmount').textContent = amount + ' SAR';
    document.getElementById('reviewPoints').textContent = points;
}

function submitTransaction() {
    const amount = parseFloat(document.getElementById('amount').value);
    const notes = document.getElementById('notes').value;
    const points = parseInt(document.getElementById('pointsValue').textContent);
    
    const formData = new FormData();
    formData.append('action', 'addTransaction');
    formData.append('cardId', selectedCardId);
    formData.append('amount', amount);
    formData.append('points', points);
    formData.append('notes', notes);
    formData.append('employeeId', <?= $employeeId ?>);
    
    const receiptInput = document.getElementById('receiptInput');
    if (receiptInput.files[0]) {
        formData.append('receipt', receiptInput.files[0]);
    }
    
    fetch('loyalty-platform/api/cards.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('successPoints').textContent = points;
            currentStep = 5;
            updateStepUI();
        } else {
            alert(data.msg || '<?= direction('Transaction failed', 'فشلت المعاملة') ?>');
        }
    });
}
</script>
