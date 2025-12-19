<!-- Transactions History View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$userId = $_SESSION['userId'];
$cardId = $_GET['cardId'] ?? null;

// Get filter parameters
$filterType = $_GET['type'] ?? 'all';
$filterPeriod = $_GET['period'] ?? 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Build where clause
$where = "userId = '$userId' AND status = '0'";
if ($cardId) {
    $where .= " AND cardId = '$cardId'";
}

// Get all transactions
$pointsResult = selectDB("points_transactions pt 
                         JOIN customer_cards cc ON pt.cardId = cc.id
                         JOIN loyalty_programs lp ON cc.programId = lp.id
                         JOIN stores s ON lp.storeId = s.id",
                         "cc.$where ORDER BY pt.date DESC LIMIT $perPage OFFSET $offset");

$stampsResult = selectDB("stamps_transactions st 
                         JOIN customer_cards cc ON st.cardId = cc.id
                         JOIN loyalty_programs lp ON cc.programId = lp.id
                         JOIN stores s ON lp.storeId = s.id",
                         "cc.$where ORDER BY st.date DESC LIMIT $perPage OFFSET $offset");

$visitsResult = selectDB("visit_transactions vt 
                         JOIN customer_cards cc ON vt.cardId = cc.id
                         JOIN loyalty_programs lp ON cc.programId = lp.id
                         JOIN stores s ON lp.storeId = s.id",
                         "cc.$where ORDER BY vt.date DESC LIMIT $perPage OFFSET $offset");

$transactions = [];

// Combine all transactions
if ($pointsResult && is_array($pointsResult)) {
    foreach ($pointsResult as $row) {
        $row['type'] = 'points';
        $transactions[] = $row;
    }
}

if ($stampsResult && is_array($stampsResult)) {
    foreach ($stampsResult as $row) {
        $row['type'] = 'stamps';
        $transactions[] = $row;
    }
}

if ($visitsResult && is_array($visitsResult)) {
    foreach ($visitsResult as $row) {
        $row['type'] = 'visit';
        $transactions[] = $row;
    }
}

// Sort by date
usort($transactions, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Apply filters
if ($filterType !== 'all') {
    $transactions = array_filter($transactions, function($t) use ($filterType) {
        if ($filterType === 'earned') {
            return in_array($t['transactionType'] ?? '', ['earned', 'purchase', 'bonus']) || isset($t['stampsAdded']);
        } elseif ($filterType === 'redeemed') {
            return in_array($t['transactionType'] ?? '', ['redeemed', 'redemption']);
        } elseif ($filterType === 'expired') {
            return ($t['transactionType'] ?? '') === 'expired';
        }
        return true;
    });
}

if ($filterPeriod !== 'all') {
    $now = time();
    $transactions = array_filter($transactions, function($t) use ($filterPeriod, $now) {
        $date = strtotime($t['date']);
        switch ($filterPeriod) {
            case 'today':
                return date('Y-m-d', $date) === date('Y-m-d', $now);
            case 'week':
                return $date >= strtotime('-7 days');
            case 'month':
                return $date >= strtotime('-30 days');
            case 'year':
                return $date >= strtotime('-1 year');
        }
        return true;
    });
}

// Calculate totals
$totalEarned = 0;
$totalRedeemed = 0;
foreach ($transactions as $t) {
    if (isset($t['pointsChange'])) {
        if ($t['pointsChange'] > 0) $totalEarned += $t['pointsChange'];
        else $totalRedeemed += abs($t['pointsChange']);
    }
}
?>

<style>
.transactions-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
    min-height: 100vh;
}

.transactions-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 20px;
}

.transactions-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.stat-card {
    background: rgba(255,255,255,0.2);
    padding: 15px;
    border-radius: 10px;
    text-align: center;
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

.filters-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
}

.filter-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.filter-select {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
}

.export-btn {
    padding: 10px 20px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.export-btn:hover {
    background: #5568d3;
}

.transactions-list {
    background: white;
    border-radius: 15px;
    overflow: hidden;
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-item:hover {
    background: #f8f9fa;
}

.transaction-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-right: 15px;
    flex-shrink: 0;
}

.icon-earned {
    background: linear-gradient(135deg, #52c41a 0%, #73d13d 100%);
    color: white;
}

.icon-redeemed {
    background: linear-gradient(135deg, #f5222d 0%, #ff4d4f 100%);
    color: white;
}

.icon-expired {
    background: linear-gradient(135deg, #faad14 0%, #ffc53d 100%);
    color: white;
}

.icon-visit {
    background: linear-gradient(135deg, #1890ff 0%, #40a9ff 100%);
    color: white;
}

.transaction-details {
    flex: 1;
}

.transaction-title {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.transaction-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #999;
}

.store-name {
    background: #e9ecef;
    padding: 3px 8px;
    border-radius: 8px;
    font-weight: 600;
}

.transaction-amount {
    text-align: right;
    margin-left: 15px;
    flex-shrink: 0;
}

.amount-value {
    font-size: 20px;
    font-weight: 700;
}

.amount-positive {
    color: #52c41a;
}

.amount-negative {
    color: #f5222d;
}

.amount-neutral {
    color: #666;
}

.amount-label {
    font-size: 11px;
    color: #999;
    margin-top: 3px;
}

.reference-code {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    color: #666;
}

.empty-transactions {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.page-btn {
    padding: 10px 15px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.page-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.page-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .filter-row {
        flex-direction: column;
    }
    
    .transaction-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .transaction-amount {
        margin-left: 0;
        text-align: left;
    }
}
</style>

<div class="transactions-container">
    <!-- Header -->
    <div class="transactions-header">
        <h1>📊 <?= direction('Transaction History', 'سجل المعاملات') ?></h1>
        <p><?= direction('View all your loyalty activity', 'عرض جميع نشاطات الولاء الخاصة بك') ?></p>
        
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value">+<?= number_format($totalEarned) ?></div>
                <div class="stat-label"><?= direction('Total Earned', 'إجمالي المكتسب') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-value">-<?= number_format($totalRedeemed) ?></div>
                <div class="stat-label"><?= direction('Total Redeemed', 'إجمالي المستبدل') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= count($transactions) ?></div>
                <div class="stat-label"><?= direction('Transactions', 'معاملة') ?></div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <input type="hidden" name="v" value="Transactions">
            <?php if ($cardId): ?>
                <input type="hidden" name="cardId" value="<?= $cardId ?>">
            <?php endif; ?>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label"><?= direction('Transaction Type', 'نوع المعاملة') ?></label>
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>
                            <?= direction('All Types', 'جميع الأنواع') ?>
                        </option>
                        <option value="earned" <?= $filterType === 'earned' ? 'selected' : '' ?>>
                            <?= direction('Earned', 'مكتسب') ?>
                        </option>
                        <option value="redeemed" <?= $filterType === 'redeemed' ? 'selected' : '' ?>>
                            <?= direction('Redeemed', 'مستبدل') ?>
                        </option>
                        <option value="expired" <?= $filterType === 'expired' ? 'selected' : '' ?>>
                            <?= direction('Expired', 'منتهي') ?>
                        </option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label"><?= direction('Time Period', 'الفترة الزمنية') ?></label>
                    <select name="period" class="filter-select" onchange="this.form.submit()">
                        <option value="all" <?= $filterPeriod === 'all' ? 'selected' : '' ?>>
                            <?= direction('All Time', 'كل الأوقات') ?>
                        </option>
                        <option value="today" <?= $filterPeriod === 'today' ? 'selected' : '' ?>>
                            <?= direction('Today', 'اليوم') ?>
                        </option>
                        <option value="week" <?= $filterPeriod === 'week' ? 'selected' : '' ?>>
                            <?= direction('This Week', 'هذا الأسبوع') ?>
                        </option>
                        <option value="month" <?= $filterPeriod === 'month' ? 'selected' : '' ?>>
                            <?= direction('This Month', 'هذا الشهر') ?>
                        </option>
                        <option value="year" <?= $filterPeriod === 'year' ? 'selected' : '' ?>>
                            <?= direction('This Year', 'هذا العام') ?>
                        </option>
                    </select>
                </div>
                
                <button type="button" class="export-btn" onclick="exportTransactions()">
                    📥 <?= direction('Export CSV', 'تصدير CSV') ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <?php if (empty($transactions)): ?>
        <div class="empty-transactions">
            <div class="empty-icon">📊</div>
            <h3><?= direction('No Transactions Found', 'لا توجد معاملات') ?></h3>
            <p><?= direction('Your transaction history will appear here', 'سيظهر سجل معاملاتك هنا') ?></p>
        </div>
    <?php else: ?>
        <div class="transactions-list">
            <?php foreach ($transactions as $transaction): ?>
                <?php
                $iconClass = 'icon-earned';
                $icon = '➕';
                $amountClass = 'amount-positive';
                
                if (isset($transaction['transactionType'])) {
                    $type = $transaction['transactionType'];
                    if ($type === 'redeemed' || $type === 'redemption') {
                        $iconClass = 'icon-redeemed';
                        $icon = '🎁';
                        $amountClass = 'amount-negative';
                    } elseif ($type === 'expired') {
                        $iconClass = 'icon-expired';
                        $icon = '⚠️';
                        $amountClass = 'amount-negative';
                    }
                } elseif ($transaction['type'] === 'visit') {
                    $iconClass = 'icon-visit';
                    $icon = '👤';
                    $amountClass = 'amount-neutral';
                }
                
                $amount = 0;
                $unit = direction('Points', 'نقطة');
                
                if (isset($transaction['pointsChange'])) {
                    $amount = $transaction['pointsChange'];
                } elseif (isset($transaction['stampsAdded'])) {
                    $amount = $transaction['stampsAdded'];
                    $unit = direction('Stamps', 'ختم');
                } elseif (isset($transaction['visitCount'])) {
                    $amount = 1;
                    $unit = direction('Visit', 'زيارة');
                }
                ?>
                
                <div class="transaction-item">
                    <div class="transaction-icon <?= $iconClass ?>">
                        <?= $icon ?>
                    </div>
                    
                    <div class="transaction-details">
                        <div class="transaction-title">
                            <?= direction($transaction['enTitle'], $transaction['arTitle']) ?>
                        </div>
                        <div class="transaction-meta">
                            <span class="store-name">
                                <?= direction($transaction['enStoreName'] ?? 'Store', $transaction['arStoreName'] ?? 'متجر') ?>
                            </span>
                            <span><?= date('M d, Y - H:i', strtotime($transaction['date'])) ?></span>
                            <?php if (!empty($transaction['reference'])): ?>
                                <span class="reference-code"><?= htmlspecialchars($transaction['reference']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="transaction-amount">
                        <div class="amount-value <?= $amountClass ?>">
                            <?= $amount > 0 ? '+' : '' ?><?= number_format($amount) ?>
                        </div>
                        <div class="amount-label"><?= $unit ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function exportTransactions() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = 'loyalty-platform/api/transactions.php?' + params.toString();
}
</script>
