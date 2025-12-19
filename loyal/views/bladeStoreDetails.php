<!-- Store Details View -->
<?php
$storeId = $_GET['id'] ?? null;
if (!$storeId) {
    header("Location: ?v=Discover");
    exit;
}

$store = getStoreDetails($storeId);
if (!$store) {
    header("Location: ?v=Discover");
    exit;
}

$userId = $_SESSION['userId'] ?? null;
$userCard = null;
if ($userId) {
    $existingCard = selectDB("customer_cards", "`userId` = '{$userId}' AND `storeId` = '{$storeId}' AND `status` = '0' LIMIT 1");
    if ($existingCard) {
        $userCard = $existingCard[0];
    }
}
?>

<style>
.store-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
}

.store-hero {
    position: relative;
    height: 300px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.store-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.store-hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 30px;
    color: white;
}

.store-main-info {
    display: flex;
    align-items: flex-end;
    gap: 20px;
}

.store-logo-large {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    border: 4px solid white;
    object-fit: cover;
    background: white;
}

.store-title-section h1 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
}

.store-rating-large {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    font-size: 18px;
}

.store-content {
    padding: 30px;
    background: white;
}

.join-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.join-btn {
    background: white;
    color: #667eea;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 15px;
    transition: transform 0.2s;
}

.join-btn:hover {
    transform: scale(1.05);
}

.already-member {
    background: rgba(255,255,255,0.2);
    padding: 20px;
    border-radius: 15px;
}

.view-card-btn {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 30px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 15px;
}

.section-tabs {
    display: flex;
    gap: 5px;
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 30px;
}

.tab-btn {
    padding: 15px 30px;
    border: none;
    background: none;
    cursor: pointer;
    font-weight: 600;
    color: #666;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.program-card {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 20px;
}

.program-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.program-name {
    font-size: 24px;
    font-weight: 700;
    color: #333;
}

.program-type-badge {
    padding: 8px 16px;
    background: #667eea;
    color: white;
    border-radius: 20px;
    font-size: 14px;
}

.program-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.program-detail-item {
    text-align: center;
    padding: 15px;
    background: white;
    border-radius: 10px;
}

.detail-value {
    font-size: 28px;
    font-weight: 700;
    color: #667eea;
}

.detail-label {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}

.rewards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.reward-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.2s;
}

.reward-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.reward-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.reward-content {
    padding: 20px;
}

.reward-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.reward-cost {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.cost-badge {
    background: #667eea;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.review-item {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer-name {
    font-weight: 600;
}

.review-stars {
    color: #ffc107;
}

.review-text {
    color: #666;
    margin-bottom: 10px;
}

.review-date {
    font-size: 12px;
    color: #999;
}

.info-section {
    margin-bottom: 30px;
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 10px;
}

.info-icon {
    font-size: 24px;
}

.info-text {
    flex: 1;
}

.branches-list {
    display: grid;
    gap: 15px;
}

.branch-card {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.branch-info h4 {
    margin: 0 0 5px 0;
}

.branch-address {
    color: #666;
    font-size: 14px;
}

.directions-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .store-hero {
        height: 200px;
    }
    
    .store-logo-large {
        width: 80px;
        height: 80px;
    }
    
    .store-title-section h1 {
        font-size: 24px;
    }
    
    .rewards-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="store-detail-container">
    <!-- Hero Section -->
    <div class="store-hero">
        <?php if ($store['coverImage']): ?>
            <img src="<?= encryptImage('logos/' . $store['coverImage']) ?>" 
                 alt="<?= $store['storeName'] ?>" 
                 class="store-cover-img">
        <?php endif; ?>
        <div class="store-hero-overlay">
            <div class="store-main-info">
                <img src="<?= encryptImage('logos/' . $store['logo']) ?>" 
                     alt="<?= $store['storeName'] ?>" 
                     class="store-logo-large">
                <div class="store-title-section">
                    <h1><?= direction($store['enStoreName'], $store['arStoreName']) ?></h1>
                    <div class="store-rating-large">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $store['rating'] ? '⭐' : '☆';
                        }
                        ?>
                        <span><?= number_format($store['rating'], 1) ?> (<?= $store['reviewCount'] ?> <?= direction('reviews', 'تقييم') ?>)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="store-content">
        <!-- Join Section -->
        <?php if (!$userCard && $userId): ?>
            <div class="join-section">
                <h2><?= direction('Join Our Loyalty Program', 'انضم لبرنامج الولاء') ?></h2>
                <p><?= direction('Start earning rewards with every purchase!', 'ابدأ بكسب المكافآت مع كل عملية شراء!') ?></p>
                <?php if ($store['programs']): ?>
                    <button class="join-btn" onclick="joinProgram(<?= $store['programs'][0]['id'] ?>)">
                        <?= direction('Join Now - FREE', 'انضم الآن - مجاناً') ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php elseif ($userCard): ?>
            <div class="join-section">
                <div class="already-member">
                    <h3>✓ <?= direction('You are a member!', 'أنت عضو!') ?></h3>
                    <p><?= direction('Current Points:', 'النقاط الحالية:') ?> <strong><?= number_format($userCard['currentPoints']) ?></strong></p>
                    <button class="view-card-btn" onclick="window.location.href='?v=CardDetails&id=<?= $userCard['id'] ?>'">
                        <?= direction('View My Card', 'عرض بطاقتي') ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="section-tabs">
            <button class="tab-btn active" onclick="showTab('overview')">
                <?= direction('Overview', 'نظرة عامة') ?>
            </button>
            <button class="tab-btn" onclick="showTab('programs')">
                <?= direction('Programs', 'البرامج') ?>
            </button>
            <button class="tab-btn" onclick="showTab('rewards')">
                <?= direction('Rewards', 'المكافآت') ?>
            </button>
            <button class="tab-btn" onclick="showTab('reviews')">
                <?= direction('Reviews', 'التقييمات') ?>
            </button>
            <button class="tab-btn" onclick="showTab('locations')">
                <?= direction('Locations', 'المواقع') ?>
            </button>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
            <div class="info-section">
                <h3 class="info-title"><?= direction('About', 'عن المتجر') ?></h3>
                <p><?= direction($store['enDescription'], $store['arDescription']) ?></p>
            </div>

            <div class="info-section">
                <h3 class="info-title"><?= direction('Contact Information', 'معلومات الاتصال') ?></h3>
                
                <?php if ($store['phone']): ?>
                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-text">
                        <strong><?= direction('Phone', 'الهاتف') ?></strong><br>
                        <?= $store['phone'] ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($store['email']): ?>
                <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-text">
                        <strong><?= direction('Email', 'البريد الإلكتروني') ?></strong><br>
                        <?= $store['email'] ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($store['website']): ?>
                <div class="info-item">
                    <div class="info-icon">🌐</div>
                    <div class="info-text">
                        <strong><?= direction('Website', 'الموقع الإلكتروني') ?></strong><br>
                        <a href="<?= $store['website'] ?>" target="_blank"><?= $store['website'] ?></a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Programs Tab -->
        <div id="programs" class="tab-content">
            <?php if ($store['programs']): ?>
                <?php foreach ($store['programs'] as $program): ?>
                    <div class="program-card">
                        <div class="program-header">
                            <h3 class="program-name">
                                <?= direction($program['enProgramName'], $program['arProgramName']) ?>
                            </h3>
                            <span class="program-type-badge">
                                <?php
                                $types = [
                                    1 => direction('Points', 'نقاط'),
                                    2 => direction('Stamps', 'أختام'),
                                    3 => direction('Visits', 'زيارات'),
                                    4 => direction('Tiered', 'متدرج'),
                                    5 => direction('Hybrid', 'مختلط')
                                ];
                                echo $types[$program['programType']] ?? '';
                                ?>
                            </span>
                        </div>
                        
                        <p><?= direction($program['enDescription'], $program['arDescription']) ?></p>
                        
                        <div class="program-details">
                            <?php if ($program['pointsPerCurrency']): ?>
                                <div class="program-detail-item">
                                    <div class="detail-value"><?= $program['pointsPerCurrency'] ?></div>
                                    <div class="detail-label"><?= direction('Points per $1', 'نقطة لكل $1') ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($program['stampsRequired']): ?>
                                <div class="program-detail-item">
                                    <div class="detail-value"><?= $program['stampsRequired'] ?></div>
                                    <div class="detail-label"><?= direction('Stamps to Complete', 'ختم لإتمام البطاقة') ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($program['welcomeBonus']): ?>
                                <div class="program-detail-item">
                                    <div class="detail-value"><?= $program['welcomeBonus'] ?></div>
                                    <div class="detail-label"><?= direction('Welcome Bonus', 'مكافأة الترحيب') ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?= direction('No programs available', 'لا توجد برامج متاحة') ?></p>
            <?php endif; ?>
        </div>

        <!-- Rewards Tab -->
        <div id="rewards" class="tab-content">
            <?php if ($store['rewards']): ?>
                <div class="rewards-grid">
                    <?php foreach ($store['rewards'] as $reward): ?>
                        <div class="reward-card">
                            <?php if ($reward['image']): ?>
                                <img src="<?= encryptImage('logos/' . $reward['image']) ?>" 
                                     alt="<?= direction($reward['enTitle'], $reward['arTitle']) ?>" 
                                     class="reward-image">
                            <?php else: ?>
                                <div class="reward-image"></div>
                            <?php endif; ?>
                            
                            <div class="reward-content">
                                <h4 class="reward-title">
                                    <?= direction($reward['enTitle'], $reward['arTitle']) ?>
                                </h4>
                                <p style="font-size: 14px; color: #666;">
                                    <?= direction($reward['enDescription'], $reward['arDescription']) ?>
                                </p>
                                
                                <div class="reward-cost">
                                    <?php if ($reward['pointsCost']): ?>
                                        <span class="cost-badge">
                                            <?= number_format($reward['pointsCost']) ?> <?= direction('Points', 'نقطة') ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($reward['stampsCost']): ?>
                                        <span class="cost-badge">
                                            <?= $reward['stampsCost'] ?> <?= direction('Stamps', 'ختم') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p><?= direction('No rewards available yet', 'لا توجد مكافآت متاحة حالياً') ?></p>
            <?php endif; ?>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews" class="tab-content">
            <?php if ($store['reviews']): ?>
                <?php foreach ($store['reviews'] as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">
                                <?php
                                $user = selectDB("users", "`id` = '{$review['userId']}'");
                                echo $user ? $user[0]['fullName'] : 'Anonymous';
                                ?>
                            </span>
                            <span class="review-stars">
                                <?php
                                for ($i = 0; $i < $review['rating']; $i++) {
                                    echo '⭐';
                                }
                                ?>
                            </span>
                        </div>
                        <p class="review-text"><?= htmlspecialchars($review['reviewText']) ?></p>
                        <span class="review-date"><?= date('M d, Y', strtotime($review['date'])) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?= direction('No reviews yet. Be the first to review!', 'لا توجد تقييمات بعد. كن أول من يقيم!') ?></p>
            <?php endif; ?>
        </div>

        <!-- Locations Tab -->
        <div id="locations" class="tab-content">
            <?php if ($store['branches']): ?>
                <div class="branches-list">
                    <?php foreach ($store['branches'] as $branch): ?>
                        <div class="branch-card">
                            <div class="branch-info">
                                <h4><?= direction($branch['enBranchName'], $branch['arBranchName']) ?></h4>
                                <p class="branch-address"><?= $branch['address'] ?></p>
                                <?php if ($branch['phone']): ?>
                                    <p style="font-size: 14px;">📞 <?= $branch['phone'] ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($branch['latitude'] && $branch['longitude']): ?>
                                <button class="directions-btn" 
                                        onclick="openDirections(<?= $branch['latitude'] ?>, <?= $branch['longitude'] ?>)">
                                    <?= direction('Directions', 'الاتجاهات') ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <strong><?= direction('Address', 'العنوان') ?></strong><br>
                        <?= $store['address'] ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById(tabName).classList.add('active');
}

function joinProgram(programId) {
    <?php if (!$userId): ?>
        alert('<?= direction('Please login first', 'يرجى تسجيل الدخول أولاً') ?>');
        window.location.href = '?v=Login';
        return;
    <?php endif; ?>
    
    fetch('loyalty-platform/api/cards.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'createCard',
            userId: <?= $userId ?>,
            storeId: <?= $storeId ?>,
            programId: programId
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('<?= direction('Welcome! Card created successfully', 'مرحباً! تم إنشاء البطاقة بنجاح') ?>');
            location.reload();
        } else {
            alert(data.msg || '<?= direction('Error creating card', 'خطأ في إنشاء البطاقة') ?>');
        }
    });
}

function openDirections(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}
</script>
