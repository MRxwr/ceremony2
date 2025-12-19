<!-- Card Details View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$cardId = $_GET['id'] ?? null;
$userId = $_SESSION['userId'];

if (!$cardId) {
    header("Location: ?v=Wallet");
    exit;
}

$card = getCardDetails($cardId, $userId);
if (!$card) {
    header("Location: ?v=Wallet");
    exit;
}

$store = $card['store'];
$program = $card['program'];
$template = $card['template'] ?? null;
$transactions = $card['recentTransactions'] ?? [];
?>

<style>
.card-detail-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
    min-height: 100vh;
}

.digital-card {
    background: <?= $template['primaryColor'] ?? '#667eea' ?>;
    color: <?= $template['textColor'] ?? 'white' ?>;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    position: relative;
    overflow: hidden;
}

.digital-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
}

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    position: relative;
    z-index: 1;
}

.card-store-logo {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: white;
    padding: 5px;
    object-fit: contain;
}

.card-store-name {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

.card-balance-section {
    text-align: center;
    padding: 30px;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    margin: 20px 0;
    position: relative;
    z-index: 1;
}

.balance-value-large {
    font-size: 56px;
    font-weight: 700;
    margin: 10px 0;
}

.balance-label-large {
    font-size: 16px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.stamps-display {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.stamp-large {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: all 0.3s;
}

.stamp-large.filled {
    background: white;
    color: <?= $template['primaryColor'] ?? '#667eea' ?>;
    border-color: white;
    transform: scale(1.1);
}

.card-number {
    text-align: center;
    font-family: 'Courier New', monospace;
    font-size: 18px;
    margin-top: 20px;
    letter-spacing: 2px;
    position: relative;
    z-index: 1;
}

.card-actions-bar {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    position: relative;
    z-index: 1;
}

.card-action-button {
    flex: 1;
    padding: 12px;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 10px;
    color: white;
    cursor: pointer;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s;
}

.card-action-button:hover {
    background: rgba(255,255,255,0.3);
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #667eea;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px 0 15px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
}

.see-all-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.transaction-list {
    background: white;
    border-radius: 15px;
    overflow: hidden;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 15px;
}

.transaction-icon.earned {
    background: #d4edda;
}

.transaction-icon.redeemed {
    background: #fff3cd;
}

.transaction-icon.expired {
    background: #f8d7da;
}

.transaction-info {
    flex: 1;
}

.transaction-desc {
    font-weight: 600;
    color: #333;
    margin-bottom: 3px;
}

.transaction-date {
    font-size: 12px;
    color: #999;
}

.transaction-amount {
    font-size: 18px;
    font-weight: 700;
}

.transaction-amount.positive {
    color: #28a745;
}

.transaction-amount.negative {
    color: #dc3545;
}

.expiry-warning {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.modal {
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

.qr-modal-content {
    background: white;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    max-width: 400px;
    width: 90%;
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

#qrCanvas {
    margin: 20px 0;
}

.qr-instructions {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-top: 20px;
    font-size: 14px;
    color: #666;
}

@media (max-width: 768px) {
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .balance-value-large {
        font-size: 42px;
    }
}
</style>

<div class="card-detail-container">
    <!-- Back Button -->
    <div style="margin-bottom: 20px;">
        <a href="?v=Wallet" style="color: #667eea; text-decoration: none; font-weight: 600;">
            ← <?= direction('Back to Wallet', 'العودة للمحفظة') ?>
        </a>
    </div>

    <!-- Digital Card -->
    <div class="digital-card">
        <div class="card-top">
            <img src="<?= encryptImage('logos/' . $store['logo']) ?>" 
                 alt="<?= $store['storeName'] ?>" 
                 class="card-store-logo">
            <h2 class="card-store-name">
                <?= direction($store['enStoreName'], $store['arStoreName']) ?>
            </h2>
        </div>

        <div class="card-balance-section">
            <?php if ($program['programType'] == 1 || $program['programType'] == 5): ?>
                <!-- Points Display -->
                <div class="balance-label-large">
                    <?= direction('Available Points', 'النقاط المتاحة') ?>
                </div>
                <div class="balance-value-large">
                    <?= number_format($card['currentPoints']) ?>
                </div>
            <?php endif; ?>

            <?php if ($program['programType'] == 2): ?>
                <!-- Stamps Display -->
                <div class="balance-label-large">
                    <?= $card['currentStamps'] ?> / <?= $program['stampsRequired'] ?> 
                    <?= direction('Stamps', 'أختام') ?>
                </div>
                <div class="stamps-display">
                    <?php for ($i = 0; $i < $program['stampsRequired']; $i++): ?>
                        <div class="stamp-large <?= $i < $card['currentStamps'] ? 'filled' : '' ?>">
                            <?= $i < $card['currentStamps'] ? '✓' : '' ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-number">
            <?= $card['cardNumber'] ?>
        </div>

        <div class="card-actions-bar">
            <button class="card-action-button" onclick="showQRModal()">
                📱 <?= direction('Show QR', 'عرض QR') ?>
            </button>
            <button class="card-action-button" onclick="window.location.href='?v=Rewards&cardId=<?= $cardId ?>'">
                🎁 <?= direction('Rewards', 'المكافآت') ?>
            </button>
        </div>
    </div>

    <!-- Expiry Warning -->
    <?php if ($card['expiringPoints'] > 0): ?>
        <div class="expiry-warning">
            <div style="font-size: 32px;">⚠️</div>
            <div>
                <strong><?= direction('Points Expiring Soon!', 'نقاط ستنتهي قريباً!') ?></strong><br>
                <span style="font-size: 14px;">
                    <?= $card['expiringPoints'] ?> <?= direction('points will expire in 30 days', 'نقطة ستنتهي في 30 يومًا') ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($card['lifetimePoints']) ?></div>
            <div class="stat-label"><?= direction('Lifetime Points', 'مجموع النقاط') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($card['totalVisits']) ?></div>
            <div class="stat-label"><?= direction('Total Visits', 'مجموع الزيارات') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $card['completedCards'] ?></div>
            <div class="stat-label"><?= direction('Cards Completed', 'بطاقات مكتملة') ?></div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="section-header">
        <h3 class="section-title"><?= direction('Recent Activity', 'النشاط الأخير') ?></h3>
        <a href="?v=Transactions&cardId=<?= $cardId ?>" class="see-all-link">
            <?= direction('See All', 'عرض الكل') ?>
        </a>
    </div>

    <div class="transaction-list">
        <?php if (empty($transactions)): ?>
            <div style="padding: 40px; text-align: center; color: #999;">
                <?= direction('No transactions yet', 'لا توجد معاملات بعد') ?>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($transactions, 0, 10) as $trans): ?>
                <div class="transaction-item">
                    <div style="display: flex; align-items: center;">
                        <div class="transaction-icon <?= $trans['type'] == 'points' && $trans['transactionType'] == 1 ? 'earned' : ($trans['transactionType'] == 2 ? 'redeemed' : 'expired') ?>">
                            <?php
                            if ($trans['type'] == 'points') {
                                echo $trans['transactionType'] == 1 ? '➕' : '➖';
                            } else {
                                echo '✓';
                            }
                            ?>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-desc"><?= htmlspecialchars($trans['description'] ?? 'Transaction') ?></div>
                            <div class="transaction-date">
                                <?= date('M d, Y - h:i A', strtotime($trans['date'])) ?>
                            </div>
                        </div>
                    </div>
                    <div class="transaction-amount <?= ($trans['type'] == 'points' && $trans['points'] > 0) || $trans['type'] == 'stamps' ? 'positive' : 'negative' ?>">
                        <?php if ($trans['type'] == 'points'): ?>
                            <?= $trans['points'] > 0 ? '+' : '' ?><?= $trans['points'] ?> pts
                        <?php elseif ($trans['type'] == 'stamps'): ?>
                            +<?= $trans['stampsAdded'] ?> <?= direction('stamps', 'ختم') ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
    <div class="qr-modal-content">
        <span class="close-modal" onclick="closeQRModal()">&times;</span>
        <h2><?= direction('Your Loyalty QR Code', 'رمز الولاء QR الخاص بك') ?></h2>
        <canvas id="qrCanvas"></canvas>
        <div class="qr-instructions">
            <?= direction(
                'Show this QR code to the cashier to earn points or redeem rewards',
                'اعرض رمز QR هذا للكاشير لكسب النقاط أو استبدال المكافآت'
            ) ?>
        </div>
        <div style="margin-top: 15px; font-family: monospace; color: #666;">
            <?= $card['cardNumber'] ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
function showQRModal() {
    document.getElementById('qrModal').style.display = 'flex';
    
    // Generate QR code
    const canvas = document.getElementById('qrCanvas');
    QRCode.toCanvas(canvas, '<?= $card['qrCode'] ?>', {
        width: 280,
        margin: 2,
        color: {
            dark: '<?= $template['primaryColor'] ?? '#667eea' ?>',
            light: '#FFFFFF'
        }
    });
}

function closeQRModal() {
    document.getElementById('qrModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('qrModal');
    if (event.target == modal) {
        closeQRModal();
    }
}
</script>
