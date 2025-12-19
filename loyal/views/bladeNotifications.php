<!-- Notifications Center View -->
<?php
if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
    header("Location: ?v=Login");
    exit;
}

$userId = $_SESSION['userId'];

// Get all notifications
$notificationsResult = selectDB("customer_notifications cn
                               LEFT JOIN stores s ON cn.storeId = s.id",
                               "cn.userId = '$userId' AND cn.hidden = '1' AND cn.status = '0'
                               ORDER BY cn.createdAt DESC LIMIT 100");

$unreadCount = 0;
$notifications = [];
if ($notificationsResult && $notificationsResult !== 0 && is_array($notificationsResult)) {
    $notifications = $notificationsResult;
    foreach ($notifications as $notif) {
        if (!$notif['isRead']) $unreadCount++;
    }
}
?>

<style>
.notifications-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
    min-height: 100vh;
}

.notifications-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notifications-header h1 {
    margin: 0;
    font-size: 28px;
}

.unread-badge {
    background: #ff4757;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
}

.notifications-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    overflow-x: auto;
}

.tab-btn {
    padding: 12px 24px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    cursor: pointer;
    white-space: nowrap;
    font-weight: 600;
    transition: all 0.2s;
}

.tab-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.notifications-actions {
    background: white;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.action-btn {
    padding: 10px 20px;
    background: #f8f9fa;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #e9ecef;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification-item {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    gap: 15px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.notification-item.unread {
    border-left: 4px solid #667eea;
    background: #f8f9fe;
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.icon-points {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.icon-stamp {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.icon-reward {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.icon-system {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.icon-achievement {
    background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.notification-message {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.5;
}

.notification-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #999;
}

.store-badge {
    background: #e9ecef;
    padding: 4px 10px;
    border-radius: 10px;
    font-weight: 600;
}

.delete-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    color: #999;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    line-height: 1;
}

.delete-btn:hover {
    color: #dc3545;
}

.empty-notifications {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.notification-detail-modal {
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

.detail-content {
    background: white;
    padding: 40px;
    border-radius: 20px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.close-detail {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 32px;
    cursor: pointer;
    color: #999;
}

@media (max-width: 768px) {
    .notifications-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>

<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <div>
            <h1>🔔 <?= direction('Notifications', 'الإشعارات') ?></h1>
            <p><?= direction('Stay updated with your loyalty activity', 'ابق على اطلاع بنشاط الولاء الخاص بك') ?></p>
        </div>
        <?php if ($unreadCount > 0): ?>
            <div class="unread-badge">
                <?= $unreadCount ?> <?= direction('Unread', 'غير مقروءة') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabs -->
    <div class="notifications-tabs">
        <button class="tab-btn active" onclick="filterNotifications('all')">
            <?= direction('All', 'الكل') ?>
        </button>
        <button class="tab-btn" onclick="filterNotifications('unread')">
            <?= direction('Unread', 'غير مقروءة') ?>
        </button>
        <button class="tab-btn" onclick="filterNotifications('points')">
            💎 <?= direction('Points', 'نقاط') ?>
        </button>
        <button class="tab-btn" onclick="filterNotifications('stamps')">
            ✓ <?= direction('Stamps', 'أختام') ?>
        </button>
        <button class="tab-btn" onclick="filterNotifications('rewards')">
            🎁 <?= direction('Rewards', 'مكافآت') ?>
        </button>
        <button class="tab-btn" onclick="filterNotifications('system')">
            📢 <?= direction('System', 'نظام') ?>
        </button>
    </div>

    <!-- Actions -->
    <div class="notifications-actions">
        <span><?= count($notifications) ?> <?= direction('notifications', 'إشعار') ?></span>
        <div>
            <button class="action-btn" onclick="markAllAsRead()">
                ✓ <?= direction('Mark all as read', 'تعليم الكل كمقروء') ?>
            </button>
            <button class="action-btn" onclick="deleteAllRead()">
                🗑️ <?= direction('Delete read', 'حذف المقروءة') ?>
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <?php if (empty($notifications)): ?>
        <div class="empty-notifications">
            <div class="empty-icon">🔔</div>
            <h3><?= direction('No Notifications', 'لا توجد إشعارات') ?></h3>
            <p><?= direction('You\'re all caught up!', 'أنت على اطلاع بكل شيء!') ?></p>
        </div>
    <?php else: ?>
        <div class="notifications-list">
            <?php foreach ($notifications as $notif): ?>
                <?php
                $iconClass = 'icon-system';
                $icon = '📢';
                
                switch ($notif['notificationType']) {
                    case 'points_earned':
                        $iconClass = 'icon-points';
                        $icon = '💎';
                        break;
                    case 'stamp_earned':
                        $iconClass = 'icon-stamp';
                        $icon = '✓';
                        break;
                    case 'reward_available':
                    case 'reward_redeemed':
                        $iconClass = 'icon-reward';
                        $icon = '🎁';
                        break;
                    case 'achievement_unlocked':
                        $iconClass = 'icon-achievement';
                        $icon = '🏆';
                        break;
                    case 'points_expiring':
                        $iconClass = 'icon-points';
                        $icon = '⚠️';
                        break;
                }
                ?>
                
                <div class="notification-item <?= !$notif['isRead'] ? 'unread' : '' ?>" 
                     data-type="<?= $notif['notificationType'] ?>"
                     data-read="<?= $notif['isRead'] ? 'yes' : 'no' ?>"
                     onclick="openNotification(<?= $notif['id'] ?>)">
                    
                    <div class="notification-icon <?= $iconClass ?>">
                        <?= $icon ?>
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-title">
                            <?= direction($notif['enTitle'], $notif['arTitle']) ?>
                        </div>
                        <div class="notification-message">
                            <?= direction($notif['enMessage'], $notif['arMessage']) ?>
                        </div>
                        <div class="notification-meta">
                            <?php if ($notif['storeId']): ?>
                                <span class="store-badge">
                                    <?= direction($notif['enStoreName'], $notif['arStoreName']) ?>
                                </span>
                            <?php endif; ?>
                            <span><?= timeAgo($notif['createdAt']) ?></span>
                        </div>
                    </div>
                    
                    <button class="delete-btn" onclick="deleteNotification(event, <?= $notif['id'] ?>)">
                        ×
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Notification Detail Modal -->
<div id="detailModal" class="notification-detail-modal">
    <div class="detail-content">
        <span class="close-detail" onclick="closeDetailModal()">&times;</span>
        <div id="detailContent"></div>
    </div>
</div>

<script>
function filterNotifications(type) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    const items = document.querySelectorAll('.notification-item');
    items.forEach(item => {
        if (type === 'all') {
            item.style.display = 'flex';
        } else if (type === 'unread') {
            item.style.display = item.dataset.read === 'no' ? 'flex' : 'none';
        } else {
            item.style.display = item.dataset.type.includes(type) ? 'flex' : 'none';
        }
    });
}

function openNotification(notifId) {
    if (event.target.classList.contains('delete-btn')) return;
    
    fetch(`loyalty-platform/api/notifications.php?action=get&id=${notifId}`)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const notif = data.data;
            document.getElementById('detailContent').innerHTML = `
                <h2><?= direction('${notif.enTitle}', '${notif.arTitle}') ?></h2>
                <p style="color: #666; margin: 20px 0;">${new Date(notif.createdAt).toLocaleString()}</p>
                <div style="line-height: 1.8;">
                    ${<?= direction('notif.enMessage', 'notif.arMessage') ?>}
                </div>
            `;
            document.getElementById('detailModal').style.display = 'flex';
            
            // Mark as read
            fetch('loyalty-platform/api/notifications.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'markRead', notificationId: notifId})
            }).then(() => {
                const item = event.currentTarget;
                item.classList.remove('unread');
                item.dataset.read = 'yes';
                updateUnreadBadge();
            });
        }
    });
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function markAllAsRead() {
    fetch('loyalty-platform/api/notifications.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'markAllRead',
            userId: <?= $userId ?>
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
                item.dataset.read = 'yes';
            });
            updateUnreadBadge();
        }
    });
}

function deleteAllRead() {
    if (!confirm('<?= direction('Delete all read notifications?', 'حذف جميع الإشعارات المقروءة؟') ?>')) {
        return;
    }
    
    fetch('loyalty-platform/api/notifications.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'deleteAllRead',
            userId: <?= $userId ?>
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.querySelectorAll('.notification-item[data-read="yes"]').forEach(item => {
                item.remove();
            });
        }
    });
}

function deleteNotification(e, notifId) {
    e.stopPropagation();
    
    if (!confirm('<?= direction('Delete this notification?', 'حذف هذا الإشعار؟') ?>')) {
        return;
    }
    
    fetch('loyalty-platform/api/notifications.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'delete', notificationId: notifId})
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            event.target.closest('.notification-item').remove();
            updateUnreadBadge();
        }
    });
}

function updateUnreadBadge() {
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    const badge = document.querySelector('.unread-badge');
    if (badge) {
        badge.innerText = `${unreadCount} <?= direction('Unread', 'غير مقروءة') ?>`;
        if (unreadCount === 0) badge.style.display = 'none';
    }
}

function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return '<?= direction('Just now', 'الآن') ?>';
    if (seconds < 3600) return Math.floor(seconds / 60) + '<?= direction('m ago', 'د') ?>';
    if (seconds < 86400) return Math.floor(seconds / 3600) + '<?= direction('h ago', 'س') ?>';
    if (seconds < 604800) return Math.floor(seconds / 86400) + '<?= direction('d ago', 'ي') ?>';
    return date.toLocaleDateString();
}
</script>

<?php
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return direction('Just now', 'الآن');
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . direction('m ago', 'د');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . direction('h ago', 'س');
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . direction('d ago', 'ي');
    } else {
        return date('M d, Y', $time);
    }
}
?>
