<!-- Loyalty Wallet View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$userId = $_SESSION['userId'];
$cards = getUserWalletCards($userId);
?>

<style>
.wallet-container {
    max-width: 480px;
    margin: 0 auto;
    padding: 20px 15px;
    background: #f5f5f5;
    min-height: 100vh;
}

.wallet-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.wallet-header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.wallet-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 15px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
}

.stat-label {
    font-size: 12px;
    opacity: 0.9;
}

.loyalty-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.loyalty-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.loyalty-card.favorited {
    border: 2px solid #ffd700;
}

.card-header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.store-logo {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    object-fit: cover;
}

.store-info {
    flex: 1;
    margin-left: 15px;
}

.store-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.program-type {
    font-size: 12px;
    color: #666;
    margin-top: 3px;
}

.favorite-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 5px;
}

.card-balance {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
}

.balance-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.balance-label {
    font-size: 14px;
    color: #666;
}

.balance-value {
    font-size: 24px;
    font-weight: bold;
    color: #667eea;
}

.stamps-visual {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    flex-wrap: wrap;
}

.stamp {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stamp.filled {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.card-action-btn {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-secondary {
    background: #e9ecef;
    color: #495057;
}

.empty-wallet {
    text-align: center;
    padding: 60px 20px;
}

.empty-wallet-icon {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.discover-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
    margin-top: 20px;
}

.expiring-notice {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 10px;
    margin-top: 10px;
    border-radius: 5px;
    font-size: 13px;
}

.floating-add-btn {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    z-index: 100;
}

.tab-navigation {
    display: flex;
    background: white;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
}

.tab-btn {
    flex: 1;
    padding: 12px;
    border: none;
    background: white;
    cursor: pointer;
    font-weight: 500;
    color: #666;
}

.tab-btn.active {
    background: #667eea;
    color: white;
}
</style>

<div class="wallet-container">
    <!-- Header -->
    <div class="wallet-header">
        <h1><?= direction('My Wallet', 'محفظتي') ?></h1>
        <div class="wallet-stats">
            <div class="stat-item">
                <div class="stat-value"><?= count($cards) ?></div>
                <div class="stat-label"><?= direction('Cards', 'بطاقات') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <?php
                    $totalPoints = 0;
                    foreach ($cards as $card) {
                        $totalPoints += $card['currentPoints'];
                    }
                    echo number_format($totalPoints);
                    ?>
                </div>
                <div class="stat-label"><?= direction('Total Points', 'مجموع النقاط') ?></div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-btn active" onclick="showTab('active')"><?= direction('Active', 'نشط') ?></button>
        <button class="tab-btn" onclick="showTab('expired')"><?= direction('Expired', 'منتهي') ?></button>
    </div>

    <!-- Cards List -->
    <div id="active-cards">
        <?php if (empty($cards)): ?>
            <div class="empty-wallet">
                <div class="empty-wallet-icon">🎴</div>
                <h3><?= direction('No Loyalty Cards Yet', 'لا توجد بطاقات ولاء بعد') ?></h3>
                <p><?= direction('Start collecting rewards from your favorite stores', 'ابدأ بجمع المكافآت من متاجرك المفضلة') ?></p>
                <a href="?v=Discover" class="discover-btn"><?= direction('Discover Stores', 'اكتشف المتاجر') ?></a>
            </div>
        <?php else: ?>
            <?php foreach ($cards as $card): ?>
                <?php
                $store = $card['store'];
                $program = $card['program'];
                $template = $card['template'] ?? null;
                $programType = $program['programType'];
                ?>
                <div class="loyalty-card <?= $card['favorited'] ? 'favorited' : '' ?>" 
                     onclick="viewCard(<?= $card['id'] ?>)">
                    
                    <!-- Card Header -->
                    <div class="card-header-section">
                        <img src="<?= encryptImage('logos/' . $store['logo']) ?>" 
                             alt="<?= $store['storeName'] ?>" 
                             class="store-logo">
                        <div class="store-info">
                            <div class="store-name">
                                <?= direction($store['enStoreName'], $store['arStoreName']) ?>
                            </div>
                            <div class="program-type">
                                <?php
                                $types = [
                                    1 => direction('Points Program', 'برنامج نقاط'),
                                    2 => direction('Stamps Card', 'بطاقة أختام'),
                                    3 => direction('Visit Based', 'بناءً على الزيارات'),
                                    4 => direction('Tiered', 'متدرج'),
                                    5 => direction('Hybrid', 'مختلط')
                                ];
                                echo $types[$programType] ?? '';
                                ?>
                            </div>
                        </div>
                        <button class="favorite-btn" onclick="toggleFavorite(event, <?= $card['id'] ?>)">
                            <?= $card['favorited'] ? '⭐' : '☆' ?>
                        </button>
                    </div>

                    <!-- Points Display -->
                    <?php if ($programType == 1 || $programType == 5): ?>
                        <div class="card-balance">
                            <div class="balance-row">
                                <span class="balance-label"><?= direction('Available Points', 'النقاط المتاحة') ?></span>
                                <span class="balance-value"><?= number_format($card['currentPoints']) ?></span>
                            </div>
                            <?php if ($card['expiringPoints'] > 0): ?>
                                <div class="expiring-notice">
                                    ⚠️ <?= $card['expiringPoints'] ?> <?= direction('points expiring in 30 days', 'نقطة ستنتهي في 30 يومًا') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Stamps Display -->
                    <?php if ($programType == 2): ?>
                        <div class="card-balance">
                            <div class="balance-label" style="margin-bottom: 10px;">
                                <?= $card['currentStamps'] ?> / <?= $program['stampsRequired'] ?> 
                                <?= direction('Stamps', 'أختام') ?>
                            </div>
                            <div class="stamps-visual">
                                <?php for ($i = 0; $i < $program['stampsRequired']; $i++): ?>
                                    <div class="stamp <?= $i < $card['currentStamps'] ? 'filled' : '' ?>">
                                        <?= $i < $card['currentStamps'] ? '✓' : '' ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <?php if ($card['completedCards'] > 0): ?>
                                <div style="margin-top: 10px; font-size: 13px; color: #28a745;">
                                    🎉 <?= $card['completedCards'] ?> <?= direction('cards completed', 'بطاقات مكتملة') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Card Actions -->
                    <div class="card-actions">
                        <button class="card-action-btn btn-primary" 
                                onclick="showQR(event, <?= $card['id'] ?>)">
                            <?= direction('Show QR', 'عرض QR') ?>
                        </button>
                        <button class="card-action-btn btn-secondary" 
                                onclick="viewRewards(event, <?= $card['id'] ?>)">
                            <?= direction('Rewards', 'المكافآت') ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Floating Add Button -->
    <button class="floating-add-btn" onclick="window.location.href='?v=Discover'">
        +
    </button>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <span class="close" onclick="closeQR()">&times;</span>
        <h2><?= direction('Show this to cashier', 'اعرض هذا للكاشير') ?></h2>
        <div id="qrCodeDisplay" style="margin: 20px 0;"></div>
        <p id="cardNumberDisplay" style="font-size: 14px; color: #666; font-family: monospace;"></p>
    </div>
</div>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #fefefe;
    padding: 30px;
    border-radius: 15px;
    position: relative;
    max-width: 90%;
}

.close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
function viewCard(cardId) {
    window.location.href = '?v=CardDetails&id=' + cardId;
}

function toggleFavorite(event, cardId) {
    event.stopPropagation();
    fetch('loyalty-platform/api/cards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'toggleFavorite',
            cardId: cardId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            location.reload();
        }
    });
}

function showQR(event, cardId) {
    event.stopPropagation();
    
    fetch('loyalty-platform/api/cards.php?action=getQR&cardId=' + cardId)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('qrModal').style.display = 'flex';
            document.getElementById('cardNumberDisplay').innerText = data.cardNumber;
            
            // Generate QR code
            document.getElementById('qrCodeDisplay').innerHTML = '';
            QRCode.toCanvas(
                document.getElementById('qrCodeDisplay').appendChild(document.createElement('canvas')),
                data.qrCode,
                { width: 250, margin: 2 }
            );
        }
    });
}

function closeQR() {
    document.getElementById('qrModal').style.display = 'none';
}

function viewRewards(event, cardId) {
    event.stopPropagation();
    window.location.href = '?v=Rewards&cardId=' + cardId;
}

function showTab(tab) {
    // Tab switching logic
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (tab === 'expired') {
        // Load expired cards
        alert('<?= direction('Expired cards feature coming soon', 'ميزة البطاقات المنتهية قريبًا') ?>');
    }
}
</script>
