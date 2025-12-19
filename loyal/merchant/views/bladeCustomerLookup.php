<!-- Merchant Customer Lookup View -->
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
?>

<style>
.lookup-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.lookup-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.lookup-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.search-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.search-box {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    font-size: 16px;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
}

.search-btn {
    padding: 15px 40px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.search-btn:hover {
    background: #5568d3;
    transform: scale(1.05);
}

.search-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-chip {
    padding: 8px 16px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s;
}

.filter-chip.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.results-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.customer-result {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.customer-result:hover {
    background: #f8f9fa;
}

.customer-result:last-child {
    border-bottom: none;
}

.customer-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    margin-right: 20px;
    flex-shrink: 0;
}

.customer-info {
    flex: 1;
}

.customer-name {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.customer-meta {
    display: flex;
    gap: 15px;
    font-size: 14px;
    color: #666;
}

.customer-stats {
    display: flex;
    gap: 20px;
    margin-left: 20px;
}

.stat-item {
    text-align: center;
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

.customer-detail-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 40px;
    border-radius: 20px;
    max-width: 800px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 32px;
    cursor: pointer;
    color: #999;
}

.detail-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.detail-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
}

.detail-name {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.detail-contact {
    font-size: 14px;
    color: #666;
}

.cards-section {
    margin-top: 30px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.card-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-info {
    flex: 1;
}

.card-program {
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.card-number {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #666;
}

.card-balance {
    text-align: right;
}

.balance-value {
    font-size: 24px;
    font-weight: 700;
    color: #667eea;
}

.balance-label {
    font-size: 11px;
    color: #999;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.action-btn {
    flex: 1;
    padding: 15px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #5568d3;
    transform: scale(1.05);
}

.empty-results {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .customer-result {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .customer-stats {
        margin-left: 0;
        width: 100%;
        justify-content: space-around;
    }
}
</style>

<div class="lookup-container">
    <!-- Header -->
    <div class="lookup-header">
        <h1>🔍 <?= direction('Customer Lookup', 'البحث عن العملاء') ?></h1>
        <p><?= direction('Search customers by name, phone, email, or card number', 'ابحث عن العملاء بالاسم أو الهاتف أو البريد الإلكتروني أو رقم البطاقة') ?></p>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="<?= direction('Enter name, phone, email, or card number...', 'أدخل الاسم أو الهاتف أو البريد الإلكتروني أو رقم البطاقة...') ?>"
                   onkeypress="if(event.key==='Enter') searchCustomers()">
            <button class="search-btn" onclick="searchCustomers()">
                🔍 <?= direction('Search', 'بحث') ?>
            </button>
        </div>
        
        <div class="search-filters">
            <div class="filter-chip active" data-filter="all" onclick="selectFilter(this)">
                <?= direction('All Customers', 'جميع العملاء') ?>
            </div>
            <div class="filter-chip" data-filter="active" onclick="selectFilter(this)">
                <?= direction('Active Cards', 'بطاقات نشطة') ?>
            </div>
            <div class="filter-chip" data-filter="high-points" onclick="selectFilter(this)">
                <?= direction('High Points', 'نقاط عالية') ?>
            </div>
            <div class="filter-chip" data-filter="recent" onclick="selectFilter(this)">
                <?= direction('Recent Activity', 'نشاط حديث') ?>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" style="display:none;">
        <div class="results-section" id="resultsContainer"></div>
    </div>
</div>

<!-- Customer Detail Modal -->
<div id="customerModal" class="customer-detail-modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeCustomerModal()">&times;</span>
        <div id="customerDetails"></div>
    </div>
</div>

<script>
const storeId = <?= $storeId ?>;
let currentFilter = 'all';

function selectFilter(element) {
    document.querySelectorAll('.filter-chip').forEach(chip => chip.classList.remove('active'));
    element.classList.add('active');
    currentFilter = element.dataset.filter;
    if (document.getElementById('searchInput').value) {
        searchCustomers();
    }
}

function searchCustomers() {
    const query = document.getElementById('searchInput').value.trim();
    if (!query && currentFilter === 'all') {
        alert('<?= direction('Please enter search term', 'الرجاء إدخال مصطلح البحث') ?>');
        return;
    }
    
    fetch(`loyalty-platform/api/customers.php?action=search&q=${encodeURIComponent(query)}&storeId=${storeId}&filter=${currentFilter}`)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            displayResults(data.data);
        } else {
            alert(data.msg || '<?= direction('Search failed', 'فشل البحث') ?>');
        }
    });
}

function displayResults(customers) {
    const container = document.getElementById('resultsContainer');
    
    if (customers.length === 0) {
        container.innerHTML = `
            <div class="empty-results">
                <div class="empty-icon">🔍</div>
                <h3><?= direction('No Customers Found', 'لم يتم العثور على عملاء') ?></h3>
                <p><?= direction('Try different search terms', 'جرب مصطلحات بحث مختلفة') ?></p>
            </div>
        `;
    } else {
        container.innerHTML = customers.map(customer => `
            <div class="customer-result" onclick="viewCustomerDetails(${customer.userId})">
                <div class="customer-avatar">
                    ${customer.firstName ? customer.firstName.charAt(0).toUpperCase() : 'C'}
                </div>
                <div class="customer-info">
                    <div class="customer-name">${customer.firstName || ''} ${customer.lastName || ''}</div>
                    <div class="customer-meta">
                        ${customer.phone ? '<span>📱 ' + customer.phone + '</span>' : ''}
                        ${customer.email ? '<span>✉️ ' + customer.email + '</span>' : ''}
                    </div>
                </div>
                <div class="customer-stats">
                    <div class="stat-item">
                        <div class="stat-value">${customer.totalCards || 0}</div>
                        <div class="stat-label"><?= direction('Cards', 'بطاقة') ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${customer.totalPoints || 0}</div>
                        <div class="stat-label"><?= direction('Points', 'نقطة') ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${customer.totalVisits || 0}</div>
                        <div class="stat-label"><?= direction('Visits', 'زيارة') ?></div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    document.getElementById('resultsSection').style.display = 'block';
}

function viewCustomerDetails(userId) {
    fetch(`loyalty-platform/api/customers.php?action=getDetails&userId=${userId}&storeId=${storeId}`)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const customer = data.data;
            document.getElementById('customerDetails').innerHTML = `
                <div class="detail-header">
                    <div class="detail-avatar">
                        ${customer.firstName ? customer.firstName.charAt(0).toUpperCase() : 'C'}
                    </div>
                    <div>
                        <div class="detail-name">${customer.firstName || ''} ${customer.lastName || ''}</div>
                        <div class="detail-contact">
                            ${customer.phone ? '📱 ' + customer.phone : ''} 
                            ${customer.email ? '✉️ ' + customer.email : ''}
                        </div>
                    </div>
                </div>
                
                <div class="cards-section">
                    <div class="section-title"><?= direction('Loyalty Cards', 'بطاقات الولاء') ?></div>
                    ${customer.cards && customer.cards.length > 0 ? customer.cards.map(card => `
                        <div class="card-item">
                            <div class="card-info">
                                <div class="card-program">${card.programName || 'Loyalty Card'}</div>
                                <div class="card-number">${card.cardNumber}</div>
                            </div>
                            <div class="card-balance">
                                <div class="balance-value">${card.currentPoints || card.currentStamps || 0}</div>
                                <div class="balance-label"><?= direction('Points', 'نقطة') ?></div>
                            </div>
                        </div>
                    `).join('') : '<p><?= direction('No cards found', 'لم يتم العثور على بطاقات') ?></p>'}
                </div>
                
                <div class="action-buttons">
                    <button class="action-btn" onclick="window.location.href='?v=Scanner'">
                        📱 <?= direction('Scan Card', 'مسح البطاقة') ?>
                    </button>
                    <button class="action-btn" onclick="viewTransactionHistory(${userId})">
                        📊 <?= direction('View History', 'عرض السجل') ?>
                    </button>
                </div>
            `;
            document.getElementById('customerModal').style.display = 'flex';
        }
    });
}

function closeCustomerModal() {
    document.getElementById('customerModal').style.display = 'none';
}

function viewTransactionHistory(userId) {
    window.location.href = `?v=Transactions&userId=${userId}`;
}
</script>
