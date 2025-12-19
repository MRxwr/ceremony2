<!-- Rewards Catalog View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$cardId = $_GET['cardId'] ?? null;
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

$rewards = getAvailableRewards($cardId);
$store = $card['store'];
?>

<style>
.rewards-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
    min-height: 100vh;
}

.rewards-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.rewards-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.current-balance-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 18px;
    font-weight: 600;
}

.rewards-filter {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    overflow-x: auto;
}

.filter-chip {
    padding: 10px 20px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}

.filter-chip.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.rewards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.reward-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
    position: relative;
}

.reward-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.reward-card.locked {
    opacity: 0.7;
}

.reward-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.reward-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.badge-available {
    background: #28a745;
    color: white;
}

.badge-locked {
    background: #6c757d;
    color: white;
}

.badge-featured {
    background: #ffc107;
    color: #333;
}

.reward-content {
    padding: 20px;
}

.reward-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.reward-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.reward-cost-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.cost-value {
    font-size: 24px;
    font-weight: 700;
    color: #667eea;
}

.cost-label {
    font-size: 12px;
    color: #666;
}

.redeem-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.redeem-btn:hover {
    background: #5568d3;
    transform: scale(1.05);
}

.redeem-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.empty-rewards {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.progress-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
}

.progress-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s;
}

.progress-text {
    font-size: 14px;
    color: #666;
    margin-top: 8px;
}

.stock-warning {
    background: #fff3cd;
    color: #856404;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 12px;
    margin-top: 10px;
    display: inline-block;
}

.redemption-modal {
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

.redemption-content {
    background: white;
    padding: 40px;
    border-radius: 20px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    position: relative;
}

.close-redemption {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 32px;
    cursor: pointer;
    color: #999;
}

.redemption-qr {
    margin: 30px 0;
}

.redemption-code {
    font-family: 'Courier New', monospace;
    font-size: 24px;
    font-weight: 700;
    color: #667eea;
    letter-spacing: 2px;
    margin: 20px 0;
}

.redemption-instructions {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .rewards-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="rewards-container">
    <!-- Header -->
    <div style="margin-bottom: 20px;">
        <a href="?v=CardDetails&id=<?= $cardId ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
            ← <?= direction('Back to Card', 'العودة للبطاقة') ?>
        </a>
    </div>

    <div class="rewards-header">
        <h1><?= direction('Rewards Catalog', 'كتالوج المكافآت') ?></h1>
        <p><?= direction($store['enStoreName'], $store['arStoreName']) ?></p>
        <div class="current-balance-badge">
            💎 <?= number_format($card['currentPoints']) ?> <?= direction('Points Available', 'نقطة متاحة') ?>
        </div>
    </div>

    <!-- Progress to Next Reward -->
    <?php if (!empty($rewards)): ?>
        <?php
        $nextReward = null;
        foreach ($rewards as $r) {
            if (!$r['canAfford'] && $r['pointsCost']) {
                $nextReward = $r;
                break;
            }
        }
        ?>
        <?php if ($nextReward): ?>
            <div class="progress-section">
                <div class="progress-title">
                    <?= direction('Progress to next reward', 'التقدم نحو المكافأة التالية') ?>: 
                    <strong><?= direction($nextReward['enTitle'], $nextReward['arTitle']) ?></strong>
                </div>
                <?php
                $progress = ($card['currentPoints'] / $nextReward['pointsCost']) * 100;
                $remaining = $nextReward['pointsCost'] - $card['currentPoints'];
                ?>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= min($progress, 100) ?>%"></div>
                </div>
                <div class="progress-text">
                    <?= number_format($remaining) ?> <?= direction('more points needed', 'نقطة إضافية مطلوبة') ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Filters -->
    <div class="rewards-filter">
        <button class="filter-chip active" onclick="filterRewards('all')">
            <?= direction('All Rewards', 'كل المكافآت') ?>
        </button>
        <button class="filter-chip" onclick="filterRewards('available')">
            <?= direction('Available', 'متاح') ?>
        </button>
        <button class="filter-chip" onclick="filterRewards('locked')">
            <?= direction('Locked', 'مقفل') ?>
        </button>
    </div>

    <!-- Rewards Grid -->
    <?php if (empty($rewards)): ?>
        <div class="empty-rewards">
            <div class="empty-icon">🎁</div>
            <h3><?= direction('No Rewards Available', 'لا توجد مكافآت متاحة') ?></h3>
            <p><?= direction('Check back later for exciting rewards!', 'تحقق لاحقاً للحصول على مكافآت مثيرة!') ?></p>
        </div>
    <?php else: ?>
        <div class="rewards-grid">
            <?php foreach ($rewards as $reward): ?>
                <div class="reward-card <?= !$reward['canAfford'] ? 'locked' : '' ?>" 
                     data-available="<?= $reward['canAfford'] ? 'yes' : 'no' ?>">
                    
                    <?php if ($reward['image']): ?>
                        <img src="<?= encryptImage('logos/' . $reward['image']) ?>" 
                             alt="<?= direction($reward['enTitle'], $reward['arTitle']) ?>" 
                             class="reward-image">
                    <?php else: ?>
                        <div class="reward-image"></div>
                    <?php endif; ?>

                    <!-- Badge -->
                    <?php if ($reward['featured']): ?>
                        <div class="reward-badge badge-featured">
                            ⭐ <?= direction('Featured', 'مميز') ?>
                        </div>
                    <?php elseif ($reward['canAfford']): ?>
                        <div class="reward-badge badge-available">
                            ✓ <?= direction('Available', 'متاح') ?>
                        </div>
                    <?php else: ?>
                        <div class="reward-badge badge-locked">
                            🔒 <?= direction('Locked', 'مقفل') ?>
                        </div>
                    <?php endif; ?>

                    <div class="reward-content">
                        <h3 class="reward-title">
                            <?= direction($reward['enTitle'], $reward['arTitle']) ?>
                        </h3>
                        
                        <p class="reward-description">
                            <?= direction($reward['enDescription'], $reward['arDescription']) ?>
                        </p>

                        <?php if (isset($reward['outOfStock']) && $reward['outOfStock']): ?>
                            <div class="stock-warning">
                                ⚠️ <?= direction('Out of Stock', 'نفذ من المخزون') ?>
                            </div>
                        <?php endif; ?>

                        <div class="reward-cost-section">
                            <div>
                                <?php if ($reward['pointsCost']): ?>
                                    <div class="cost-value"><?= number_format($reward['pointsCost']) ?></div>
                                    <div class="cost-label"><?= direction('Points', 'نقطة') ?></div>
                                <?php elseif ($reward['stampsCost']): ?>
                                    <div class="cost-value"><?= $reward['stampsCost'] ?></div>
                                    <div class="cost-label"><?= direction('Stamps', 'ختم') ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button class="redeem-btn" 
                                    onclick="redeemReward(<?= $reward['id'] ?>, '<?= addslashes(direction($reward['enTitle'], $reward['arTitle'])) ?>')"
                                    <?= (!$reward['canAfford'] || (isset($reward['outOfStock']) && $reward['outOfStock'])) ? 'disabled' : '' ?>>
                                <?= direction('Redeem', 'استبدل') ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Redemption Success Modal -->
<div id="redemptionModal" class="redemption-modal">
    <div class="redemption-content">
        <span class="close-redemption" onclick="closeRedemptionModal()">&times;</span>
        <div style="font-size: 64px; margin-bottom: 20px;">🎉</div>
        <h2><?= direction('Reward Redeemed!', 'تم استبدال المكافأة!') ?></h2>
        <p id="rewardName" style="font-size: 18px; margin: 20px 0;"></p>
        
        <div class="redemption-qr">
            <canvas id="redemptionQRCanvas"></canvas>
        </div>
        
        <div class="redemption-code" id="redemptionCode"></div>
        
        <div class="redemption-instructions">
            <strong><?= direction('How to use:', 'كيفية الاستخدام:') ?></strong><br>
            <?= direction(
                'Show this QR code or redemption code to the cashier to claim your reward',
                'اعرض رمز QR أو رمز الاستبدال هذا للكاشير للحصول على مكافأتك'
            ) ?>
        </div>

        <button class="redeem-btn" style="margin-top: 20px;" onclick="window.location.href='?v=CardDetails&id=<?= $cardId ?>'">
            <?= direction('Back to Card', 'العودة للبطاقة') ?>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
function filterRewards(type) {
    document.querySelectorAll('.filter-chip').forEach(chip => chip.classList.remove('active'));
    event.target.classList.add('active');
    
    const cards = document.querySelectorAll('.reward-card');
    cards.forEach(card => {
        if (type === 'all') {
            card.style.display = 'block';
        } else if (type === 'available') {
            card.style.display = card.dataset.available === 'yes' ? 'block' : 'none';
        } else if (type === 'locked') {
            card.style.display = card.dataset.available === 'no' ? 'block' : 'none';
        }
    });
}

function redeemReward(rewardId, rewardName) {
    if (!confirm('<?= direction('Confirm redemption?', 'تأكيد الاستبدال؟') ?>')) {
        return;
    }

    fetch('loyalty-platform/api/rewards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'redeem',
            cardId: <?= $cardId ?>,
            rewardId: rewardId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('rewardName').innerText = rewardName;
            document.getElementById('redemptionCode').innerText = data.redemptionCode;
            document.getElementById('redemptionModal').style.display = 'flex';
            
            // Generate QR
            const canvas = document.getElementById('redemptionQRCanvas');
            QRCode.toCanvas(canvas, data.qrCode, {
                width: 250,
                margin: 2
            });
        } else {
            alert(data.msg || '<?= direction('Redemption failed', 'فشل الاستبدال') ?>');
        }
    })
    .catch(err => {
        alert('<?= direction('Error processing redemption', 'خطأ في معالجة الاستبدال') ?>');
    });
}

function closeRedemptionModal() {
    document.getElementById('redemptionModal').style.display = 'none';
}
</script>
