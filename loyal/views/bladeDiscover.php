<!-- Store Discovery View -->
<?php
$categoryFilter = $_GET['category'] ?? null;
$searchQuery = $_GET['search'] ?? '';

$filters = [];
if ($categoryFilter) $filters['categoryId'] = $categoryFilter;
if ($searchQuery) $filters['search'] = $searchQuery;

$stores = getAllStores($filters);
$categories = selectDB("categories", "`status` = '0' AND `hidden` = '1' ORDER BY `rank` ASC");
?>

<style>
.discover-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.search-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.search-box {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 15px 50px 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
}

.category-filter {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 20px 0;
    -webkit-overflow-scrolling: touch;
}

.category-chip {
    padding: 10px 20px;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: white;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    text-decoration: none;
    color: #495057;
}

.category-chip.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.stores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.store-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.store-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.store-cover {
    width: 100%;
    height: 150px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.store-content {
    padding: 20px;
}

.store-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.store-logo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    margin-top: -40px;
    border: 3px solid white;
    background: white;
}

.store-info {
    flex: 1;
    margin-left: 15px;
}

.store-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 5px 0;
}

.store-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #ffc107;
}

.store-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.store-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
    font-size: 13px;
    color: #666;
}

.program-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #e7f3ff;
    color: #0066cc;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.empty-results {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.location-section {
    background: #e7f3ff;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.location-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
}

.view-mode-toggle {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.view-btn {
    padding: 8px 15px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 8px;
    cursor: pointer;
}

.view-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .stores-grid {
        grid-template-columns: 1fr;
    }
    
    .store-cover {
        height: 120px;
    }
}
</style>

<div class="discover-container">
    <!-- Header -->
    <h1><?= direction('Discover Loyalty Programs', 'اكتشف برامج الولاء') ?></h1>
    
    <!-- Search Section -->
    <div class="search-section">
        <form method="GET" class="search-box">
            <input type="hidden" name="v" value="Discover">
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="<?= direction('Search for stores, brands, categories...', 'ابحث عن المتاجر، العلامات التجارية، الفئات...') ?>"
                   value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit" class="search-btn">
                <?= direction('Search', 'بحث') ?>
            </button>
        </form>
    </div>

    <!-- Location Section -->
    <div class="location-section">
        <span style="font-size: 20px;">📍</span>
        <div style="flex: 1;">
            <strong><?= direction('Find stores near you', 'اعثر على متاجر بالقرب منك') ?></strong>
        </div>
        <button class="location-btn" onclick="findNearby()">
            <?= direction('Use My Location', 'استخدم موقعي') ?>
        </button>
    </div>

    <!-- Category Filter -->
    <?php if ($categories): ?>
    <div class="category-filter">
        <a href="?v=Discover" class="category-chip <?= !$categoryFilter ? 'active' : '' ?>">
            <?= direction('All', 'الكل') ?>
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="?v=Discover&category=<?= $category['id'] ?>" 
               class="category-chip <?= $categoryFilter == $category['id'] ? 'active' : '' ?>">
                <?= direction($category['enTitle'], $category['arTitle']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- View Mode Toggle -->
    <div class="view-mode-toggle">
        <button class="view-btn active" onclick="setView('grid')">
            <span>⊞</span> <?= direction('Grid', 'شبكة') ?>
        </button>
        <button class="view-btn" onclick="setView('list')">
            <span>☰</span> <?= direction('List', 'قائمة') ?>
        </button>
    </div>

    <!-- Stores Grid -->
    <?php if (empty($stores)): ?>
        <div class="empty-results">
            <div class="empty-icon">🔍</div>
            <h3><?= direction('No stores found', 'لم يتم العثور على متاجر') ?></h3>
            <p><?= direction('Try adjusting your search or filters', 'حاول تعديل البحث أو الفلاتر') ?></p>
        </div>
    <?php else: ?>
        <div class="stores-grid">
            <?php foreach ($stores as $store): ?>
                <div class="store-card" onclick="viewStore(<?= $store['id'] ?>)">
                    <?php if ($store['featured']): ?>
                        <div class="featured-badge">✨ <?= direction('Featured', 'مميز') ?></div>
                    <?php endif; ?>
                    
                    <img src="<?= $store['coverImage'] ? encryptImage('logos/' . $store['coverImage']) : '' ?>" 
                         alt="<?= $store['storeName'] ?>" 
                         class="store-cover"
                         onerror="this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'">
                    
                    <div class="store-content">
                        <div class="store-header">
                            <img src="<?= encryptImage('logos/' . $store['logo']) ?>" 
                                 alt="<?= $store['storeName'] ?>" 
                                 class="store-logo">
                            <div class="store-info">
                                <h3 class="store-name">
                                    <?= direction($store['enStoreName'], $store['arStoreName']) ?>
                                </h3>
                                <div class="store-rating">
                                    <?php
                                    $rating = $store['rating'];
                                    for ($i = 0; $i < 5; $i++) {
                                        echo $i < $rating ? '⭐' : '☆';
                                    }
                                    ?>
                                    <span style="color: #666;">(<?= $store['reviewCount'] ?>)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="store-description">
                            <?= direction($store['enDescription'], $store['arDescription']) ?>
                        </div>
                        
                        <div class="store-meta">
                            <span class="program-badge">
                                <?php
                                // Get program types for this store
                                $programs = selectDB("loyalty_programs", "`storeId` = '{$store['id']}' AND `status` = '0' LIMIT 1");
                                if ($programs) {
                                    $types = [
                                        1 => direction('Points', 'نقاط'),
                                        2 => direction('Stamps', 'أختام'),
                                        3 => direction('Visits', 'زيارات'),
                                        4 => direction('Tiered', 'متدرج'),
                                        5 => direction('Hybrid', 'مختلط')
                                    ];
                                    echo $types[$programs[0]['programType']] ?? '';
                                }
                                ?>
                            </span>
                            <span>
                                👥 <?= number_format($store['totalCustomers']) ?> 
                                <?= direction('members', 'عضو') ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function viewStore(storeId) {
    window.location.href = '?v=StoreDetails&id=' + storeId;
}

function findNearby() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                window.location.href = `?v=Discover&lat=${lat}&lng=${lng}&nearby=1`;
            },
            function(error) {
                alert('<?= direction('Please enable location access', 'يرجى تفعيل الوصول للموقع') ?>');
            }
        );
    } else {
        alert('<?= direction('Location not supported by your browser', 'الموقع غير مدعوم من متصفحك') ?>');
    }
}

function setView(mode) {
    document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.view-btn').classList.add('active');
    
    const grid = document.querySelector('.stores-grid');
    if (mode === 'list') {
        grid.style.gridTemplateColumns = '1fr';
    } else {
        grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
    }
}
</script>
