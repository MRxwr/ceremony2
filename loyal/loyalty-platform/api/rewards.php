<?php
/**
 * Rewards API Endpoint
 * Handles reward catalog and redemption operations
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
        case 'getCatalog':
            getCatalog();
            break;
            
        case 'getReward':
            getReward();
            break;
            
        case 'getRedemptions':
            getRedemptions();
            break;
            
        case 'checkRedemption':
            checkRedemption();
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
        case 'redeem':
            redeemReward($input);
            break;
            
        case 'processRedemption':
            processRedemption($input);
            break;
            
        case 'cancelRedemption':
            cancelRedemption($input);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
}

/**
 * Get rewards catalog for a specific card
 */
function getCatalog() {
    global $response, $dbconnect;
    
    $cardId = $_GET['cardId'] ?? null;
    if (!$cardId) {
        throw new Exception('Card ID required');
    }
    
    // Verify card exists and get details
    $card = getCardDetails($cardId, null);
    if (!$card) {
        throw new Exception('Card not found');
    }
    
    $storeId = $card['storeId'];
    $currentPoints = $card['currentPoints'];
    $currentStamps = $card['currentStamps'];
    
    // Get rewards catalog
    $result = selectDB("rewards_catalog rc", "rc.storeId = '$storeId' AND rc.hidden = '1' AND rc.status = '0' 
                       AND (rc.validFrom IS NULL OR rc.validFrom <= NOW())
                       AND (rc.validUntil IS NULL OR rc.validUntil >= NOW())
                       ORDER BY rc.featured DESC, rc.pointsCost ASC, rc.stampsCost ASC");
    $rewards = [];
    
    if (!$result || $result === 0 || !is_array($result)) {
        $response['ok'] = true;
        $response['data'] = [
            'rewards' => [],
            'currentBalance' => [
                'points' => $currentPoints,
                'stamps' => $currentStamps
            ]
        ];
        return;
    }
    
    foreach ($result as $row) {
        // Check if customer can afford
        $canAfford = false;
        if ($row['pointsCost'] && $row['pointsCost'] <= $currentPoints) {
            $canAfford = true;
        } elseif ($row['stampsCost'] && $row['stampsCost'] <= $currentStamps) {
            $canAfford = true;
        }
        
        // Check stock
        $outOfStock = false;
        if ($row['stockLimit'] && $row['stockLimit'] > 0) {
            $redeemedResult = selectDB("redemptions", "`rewardId`='{$row['id']}' AND `status`='completed'");
            if ($redeemedResult && $redeemedResult !== 0 && is_array($redeemedResult) && count($redeemedResult) >= $row['stockLimit']) {
                $outOfStock = true;
            }
        }
        
        $row['canAfford'] = $canAfford;
        $row['outOfStock'] = $outOfStock;
        $rewards[] = $row;
    }
    
    $response['ok'] = true;
    $response['data'] = [
        'rewards' => $rewards,
        'currentBalance' => [
            'points' => $currentPoints,
            'stamps' => $currentStamps
        ]
    ];
}

/**
 * Get single reward details
 */
function getReward() {
    global $response;
    
    $rewardId = $_GET['rewardId'] ?? null;
    if (!$rewardId) {
        throw new Exception('Reward ID required');
    }
    
    $result = selectDB("rewards_catalog", "`id`='$rewardId' AND `hidden`='1' AND `status`='0' LIMIT 1");
    if (!$result || $result === 0 || !is_array($result) || count($result) === 0) {
        throw new Exception('Reward not found');
    }
    
    $response['ok'] = true;
    $response['data'] = $result[0];
}

/**
 * Redeem a reward
 */
function redeemReward($input) {
    global $response, $dbconnect;
    
    $cardId = $input['cardId'] ?? null;
    $rewardId = $input['rewardId'] ?? null;
    
    if (!$cardId || !$rewardId) {
        throw new Exception('Card ID and Reward ID required');
    }
    
    // Start transaction
    $dbconnect->begin_transaction();
    
    try {
        // Get card details
        $card = getCardDetails($cardId, null);
        if (!$card) {
            throw new Exception('Card not found');
        }
        
        // Get reward details
        $rewardResult = selectDB("rewards_catalog", "`id`='$rewardId' AND `hidden`='1' AND `status`='0' LIMIT 1");
        if (!$rewardResult || $rewardResult === 0 || !is_array($rewardResult) || count($rewardResult) === 0) {
            throw new Exception('Reward not found');
        }
        $reward = $rewardResult[0];
        
        // Verify same store
        if ($reward['storeId'] != $card['storeId']) {
            throw new Exception('Reward not available for this card');
        }
        
        // Check stock
        if ($reward['stockLimit'] && $reward['stockLimit'] > 0) {
            $redeemedResult = selectDB("redemptions", "`rewardId`='$rewardId' AND `status`='completed'");
            if ($redeemedResult && $redeemedResult !== 0 && is_array($redeemedResult) && count($redeemedResult) >= $reward['stockLimit']) {
                throw new Exception('Reward out of stock');
            }
        }
        
        // Check if customer can afford
        $costType = '';
        $costAmount = 0;
        
        if ($reward['pointsCost']) {
            if ($card['currentPoints'] < $reward['pointsCost']) {
                throw new Exception('Insufficient points');
            }
            $costType = 'points';
            $costAmount = $reward['pointsCost'];
        } elseif ($reward['stampsCost']) {
            if ($card['currentStamps'] < $reward['stampsCost']) {
                throw new Exception('Insufficient stamps');
            }
            $costType = 'stamps';
            $costAmount = $reward['stampsCost'];
        }
        
        // Generate redemption code
        $redemptionCode = 'RED-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        
        // Create redemption record
        $redemptionData = [
            'cardId' => $cardId,
            'rewardId' => $rewardId,
            'redemptionCode' => $redemptionCode,
            'costType' => $costType,
            'costAmount' => $costAmount,
            'status' => 'pending',
            'redeemedAt' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d H:i:s'),
            'hidden' => '1',
            'enTitle' => $reward['enTitle'],
            'arTitle' => $reward['arTitle'],
            'enDetails' => 'Redemption pending validation',
            'arDetails' => 'الاستبدال في انتظار التحقق'
        ];
        
        insertDB('redemptions', $redemptionData);
        $redemptionId = $dbconnect->insert_id;
        
        // Deduct points/stamps from card
        if ($costType === 'points') {
            $newPoints = $card['currentPoints'] - $costAmount;
            $dbconnect->query("UPDATE customer_cards SET currentPoints = $newPoints WHERE id = '$cardId'");
            
            // Record points transaction
            $transData = [
                'cardId' => $cardId,
                'transactionType' => 'redeemed',
                'pointsChange' => -$costAmount,
                'balanceAfter' => $newPoints,
                'reference' => "Redemption: $redemptionCode",
                'date' => date('Y-m-d H:i:s'),
                'hidden' => '1',
                'enTitle' => 'Reward Redeemed',
                'arTitle' => 'تم استبدال المكافأة',
                'enDetails' => $reward['enTitle'],
                'arDetails' => $reward['arTitle'],
                'status' => '0'
            ];
            insertDB('points_transactions', $transData);
        } else {
            $newStamps = $card['currentStamps'] - $costAmount;
            $dbconnect->query("UPDATE customer_cards SET currentStamps = $newStamps WHERE id = '$cardId'");
            
            // Record stamp transaction
            $transData = [
                'cardId' => $cardId,
                'stampsAdded' => -$costAmount,
                'totalStamps' => $newStamps,
                'reference' => "Redemption: $redemptionCode",
                'date' => date('Y-m-d H:i:s'),
                'hidden' => '1',
                'enTitle' => 'Reward Redeemed',
                'arTitle' => 'تم استبدال المكافأة',
                'enDetails' => $reward['enTitle'],
                'arDetails' => $reward['arTitle'],
                'status' => '0'
            ];
            insertDB('stamps_transactions', $transData);
        }
        
        // Send notification
        sendRedemptionNotification($card['userId'], $redemptionCode, $reward['enTitle'], $reward['arTitle']);
        
        // Generate QR code data
        $qrData = encryptQRData([
            'type' => 'redemption',
            'redemptionId' => $redemptionId,
            'code' => $redemptionCode,
            'cardId' => $cardId,
            'rewardId' => $rewardId,
            'timestamp' => time()
        ]);
        
        $dbconnect->commit();
        
        $response['ok'] = true;
        $response['msg'] = 'Reward redeemed successfully';
        $response['data'] = [
            'redemptionId' => $redemptionId,
            'redemptionCode' => $redemptionCode,
            'qrCode' => $qrData
        ];
        $response['redemptionCode'] = $redemptionCode;
        $response['qrCode'] = $qrData;
        
    } catch (Exception $e) {
        $dbconnect->rollback();
        throw $e;
    }
}

/**
 * Process/validate redemption by merchant
 */
function processRedemption($input) {
    global $response, $dbconnect;
    
    $redemptionCode = $input['redemptionCode'] ?? null;
    $storeId = $input['storeId'] ?? null;
    
    if (!$redemptionCode || !$storeId) {
        throw new Exception('Redemption code and store ID required');
    }
    
    // Find redemption
    $result = selectDB("redemptions r 
                       JOIN customer_cards c ON r.cardId = c.id
                       JOIN loyalty_programs p ON c.programId = p.id",
                       "r.redemptionCode = '$redemptionCode' AND p.storeId = '$storeId' AND r.status = 'pending' LIMIT 1");
    
    if (!$result || $result === 0 || !is_array($result) || count($result) === 0) {
        throw new Exception('Invalid or already processed redemption code');
    }
    
    $redemption = $result[0];
    $redemption = $result[0];
    
    // Update redemption status
    $dbconnect->query("UPDATE redemptions SET status = 'completed', validatedAt = NOW() WHERE redemptionCode = '$redemptionCode'");
    
    $response['ok'] = true;
    $response['msg'] = 'Redemption validated successfully';
    $response['data'] = $redemption;
}

/**
 * Get customer redemptions
 */
function getRedemptions() {
    global $response;
    
    $cardId = $_GET['cardId'] ?? null;
    $userId = $_GET['userId'] ?? null;
    
    if (!$cardId && !$userId) {
        throw new Exception('Card ID or User ID required');
    }
    
    $condition = $cardId ? "r.cardId = '$cardId'" : "c.userId = '$userId'";
    
    $result = selectDB("redemptions r 
                       JOIN customer_cards c ON r.cardId = c.id
                       JOIN rewards_catalog rc ON r.rewardId = rc.id
                       JOIN stores s ON rc.storeId = s.id",
                       "$condition ORDER BY r.redeemedAt DESC");
    
    $redemptions = [];
    if ($result && $result !== 0 && is_array($result)) {
        $redemptions = $result;
    }
    
    $response['ok'] = true;
    $response['data'] = $redemptions;
}

/**
 * Check redemption status
 */
function checkRedemption() {
    global $response;
    
    $redemptionCode = $_GET['code'] ?? null;
    if (!$redemptionCode) {
        throw new Exception('Redemption code required');
    }
    
    $result = selectDB("redemptions", "`redemptionCode`='$redemptionCode' LIMIT 1");
    if (!$result || $result === 0 || !is_array($result) || count($result) === 0) {
        throw new Exception('Redemption not found');
    }
    
    $response['ok'] = true;
    $response['data'] = $result[0];
}

/**
 * Cancel pending redemption
 */
function cancelRedemption($input) {
    global $response, $dbconnect;
    
    $redemptionCode = $input['redemptionCode'] ?? null;
    if (!$redemptionCode) {
        throw new Exception('Redemption code required');
    }
    
    $dbconnect->begin_transaction();
    
    try {
        // Get redemption
        $result = selectDB("redemptions", "`redemptionCode`='$redemptionCode' AND `status`='pending' LIMIT 1");
        if (!$result || $result === 0 || !is_array($result) || count($result) === 0) {
            throw new Exception('Redemption not found or already processed');
        }
        
        $redemption = $result[0];
        
        // Refund points/stamps
        if ($redemption['costType'] === 'points') {
            $dbconnect->query("UPDATE customer_cards SET currentPoints = currentPoints + {$redemption['costAmount']} WHERE id = '{$redemption['cardId']}'");
        } else {
            $dbconnect->query("UPDATE customer_cards SET currentStamps = currentStamps + {$redemption['costAmount']} WHERE id = '{$redemption['cardId']}'");
        }
        
        // Cancel redemption
        $dbconnect->query("UPDATE redemptions SET status = 'cancelled' WHERE redemptionCode = '$redemptionCode'");
        
        $dbconnect->commit();
        
        $response['ok'] = true;
        $response['msg'] = 'Redemption cancelled successfully';
        
    } catch (Exception $e) {
        $dbconnect->rollback();
        throw $e;
    }
}
