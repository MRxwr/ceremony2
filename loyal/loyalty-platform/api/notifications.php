<?php
/**
 * Notifications API Endpoint
 * Handles notification operations
 */

header('Content-Type: application/json');
require_once '../dashboard/includes/config.php';
require_once '../dashboard/includes/functions.php';

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize response
$response = ['ok' => false, 'msg' => '', 'data' => null];

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        handleGetRequest();
    } elseif ($method === 'POST') {
        handlePostRequest();
    } else {
        throw new Exception('Method not allowed');
    }
    
} catch (Exception $e) {
    $response['msg'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;

/**
 * Handle GET requests
 */
function handleGetRequest() {
    global $response;
    
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'get':
            getNotification();
            break;
            
        case 'getAll':
            getAllNotifications();
            break;
            
        case 'getUnreadCount':
            getUnreadCount();
            break;
            
        default:
            throw new Exception('Invalid action');
    }
}

/**
 * Handle POST requests
 */
function handlePostRequest() {
    global $response;
    
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    $action = $input['action'] ?? '';
    
    switch ($action) {
        case 'markRead':
            markAsRead($input);
            break;
            
        case 'markAllRead':
            markAllAsRead($input);
            break;
            
        case 'delete':
            deleteNotification($input);
            break;
            
        case 'deleteAllRead':
            deleteAllRead($input);
            break;
            
        case 'send':
            sendNotification($input);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
}

/**
 * Get single notification
 */
function getNotification() {
    global $response;
    
    $notifId = $_GET['id'] ?? null;
    if (!$notifId) {
        throw new Exception('Notification ID required');
    }
    
    $result = selectDB("customer_notifications", "`id`='$notifId' AND `status`='0' LIMIT 1");
    if (!$result || $result === 0 || !is_array($result) || count($result) === 0) {
        throw new Exception('Notification not found');
    }
    
    $response['ok'] = true;
    $response['data'] = $result[0];
}

/**
 * Get all notifications for a user
 */
function getAllNotifications() {
    global $response;
    
    $userId = $_GET['userId'] ?? null;
    if (!$userId) {
        throw new Exception('User ID required');
    }
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $type = $_GET['type'] ?? null;
    
    $where = "cn.userId = '$userId' AND cn.hidden = '1' AND cn.status = '0'";
    if ($type) {
        $where .= " AND cn.notificationType = '$type'";
    }
    
    $result = selectDB("customer_notifications cn
                       LEFT JOIN stores s ON cn.storeId = s.id",
                       "$where ORDER BY cn.createdAt DESC LIMIT $limit OFFSET $offset");
    
    $notifications = [];
    if ($result && $result !== 0 && is_array($result)) {
        $notifications = $result;
    }
    
    $response['ok'] = true;
    $response['data'] = $notifications;
}

/**
 * Get unread notification count
 */
function getUnreadCount() {
    global $response;
    
    $userId = $_GET['userId'] ?? null;
    if (!$userId) {
        throw new Exception('User ID required');
    }
    
    $result = selectDB("customer_notifications",
                       "`userId`='$userId' AND `isRead`=0 AND `hidden`='1' AND `status`='0'");
    
    $count = ($result && is_array($result)) ? count($result) : 0;
    
    $response['ok'] = true;
    $response['data'] = ['count' => $count];
}

/**
 * Mark notification as read
 */
function markAsRead($input) {
    global $response, $dbconnect;
    
    $notifId = $input['notificationId'] ?? null;
    if (!$notifId) {
        throw new Exception('Notification ID required');
    }
    
    $dbconnect->query("UPDATE customer_notifications 
                      SET isRead = 1, readAt = NOW() 
                      WHERE id = '$notifId'");
    
    $response['ok'] = true;
    $response['msg'] = 'Notification marked as read';
}

/**
 * Mark all notifications as read
 */
function markAllAsRead($input) {
    global $response, $dbconnect;
    
    $userId = $input['userId'] ?? null;
    if (!$userId) {
        throw new Exception('User ID required');
    }
    
    $dbconnect->query("UPDATE customer_notifications 
                      SET isRead = 1, readAt = NOW() 
                      WHERE userId = '$userId' AND isRead = 0");
    
    $response['ok'] = true;
    $response['msg'] = 'All notifications marked as read';
}

/**
 * Delete notification
 */
function deleteNotification($input) {
    global $response, $dbconnect;
    
    $notifId = $input['notificationId'] ?? null;
    if (!$notifId) {
        throw new Exception('Notification ID required');
    }
    
    $dbconnect->query("UPDATE customer_notifications 
                      SET status = '1' 
                      WHERE id = '$notifId'");
    
    $response['ok'] = true;
    $response['msg'] = 'Notification deleted';
}

/**
 * Delete all read notifications
 */
function deleteAllRead($input) {
    global $response, $dbconnect;
    
    $userId = $input['userId'] ?? null;
    if (!$userId) {
        throw new Exception('User ID required');
    }
    
    $dbconnect->query("UPDATE customer_notifications 
                      SET status = '1' 
                      WHERE userId = '$userId' AND isRead = 1");
    
    $response['ok'] = true;
    $response['msg'] = 'All read notifications deleted';
}

/**
 * Send notification to user
 */
function sendNotification($input) {
    global $response;
    
    $userId = $input['userId'] ?? null;
    $type = $input['type'] ?? null;
    $enTitle = $input['enTitle'] ?? '';
    $arTitle = $input['arTitle'] ?? '';
    $enMessage = $input['enMessage'] ?? '';
    $arMessage = $input['arMessage'] ?? '';
    $storeId = $input['storeId'] ?? null;
    
    if (!$userId || !$type || !$enTitle || !$enMessage) {
        throw new Exception('Missing required fields');
    }
    
    $notifData = [
        'userId' => $userId,
        'notificationType' => $type,
        'enTitle' => $enTitle,
        'arTitle' => $arTitle,
        'enMessage' => $enMessage,
        'arMessage' => $arMessage,
        'storeId' => $storeId,
        'isRead' => 0,
        'createdAt' => date('Y-m-d H:i:s'),
        'date' => date('Y-m-d H:i:s'),
        'hidden' => '1',
        'enDetails' => $enMessage,
        'arDetails' => $arMessage,
        'status' => '0'
    ];
    
    $result = insertDB('customer_notifications', $notifData);
    
    if ($result) {
        $response['ok'] = true;
        $response['msg'] = 'Notification sent successfully';
    } else {
        throw new Exception('Failed to send notification');
    }
}
