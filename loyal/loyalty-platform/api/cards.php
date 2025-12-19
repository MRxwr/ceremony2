<?php
// =====================================================
// LOYALTY WALLET - CARDS API ENDPOINT
// =====================================================
header('Content-Type: application/json');
require_once('../../dashboard/includes/config.php');
require_once('../../dashboard/includes/functions.php');

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get request data
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

// Response helper
function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// =====================================================
// GET METHODS
// =====================================================

if ($method === 'GET') {
    switch ($action) {
        case 'getWallet':
            $userId = $_GET['userId'] ?? null;
            if (!$userId) respond(['ok' => false, 'msg' => 'User ID required'], 400);
            
            $cards = getUserWalletCards($userId);
            respond(['ok' => true, 'cards' => $cards]);
            break;
            
        case 'getCard':
            $cardId = $_GET['cardId'] ?? null;
            $userId = $_GET['userId'] ?? null;
            if (!$cardId) respond(['ok' => false, 'msg' => 'Card ID required'], 400);
            
            $card = getCardDetails($cardId, $userId);
            if ($card) {
                respond(['ok' => true, 'card' => $card]);
            } else {
                respond(['ok' => false, 'msg' => 'Card not found'], 404);
            }
            break;
            
        case 'getQR':
            $cardId = $_GET['cardId'] ?? null;
            if (!$cardId) respond(['ok' => false, 'msg' => 'Card ID required'], 400);
            
            $card = selectDB("customer_cards", "`id` = '{$cardId}' AND `status` = '0'");
            if ($card) {
                respond([
                    'ok' => true,
                    'qrCode' => $card[0]['qrCode'],
                    'cardNumber' => $card[0]['cardNumber'],
                    'barcode' => $card[0]['barcode']
                ]);
            } else {
                respond(['ok' => false, 'msg' => 'Card not found'], 404);
            }
            break;
            
        case 'getTransactions':
            $cardId = $_GET['cardId'] ?? null;
            $limit = $_GET['limit'] ?? 50;
            if (!$cardId) respond(['ok' => false, 'msg' => 'Card ID required'], 400);
            
            $transactions = getCardTransactions($cardId, $limit);
            respond(['ok' => true, 'transactions' => $transactions]);
            break;
            
        case 'scanQR':
            // Merchant scans customer QR
            $qrCode = $_GET['qr'] ?? null;
            if (!$qrCode) respond(['ok' => false, 'msg' => 'QR code required'], 400);
            
            $card = getCardByQR($qrCode);
            if ($card) {
                respond(['ok' => true, 'card' => $card]);
            } else {
                respond(['ok' => false, 'msg' => 'Invalid QR code'], 404);
            }
            break;
            
        default:
            respond(['ok' => false, 'msg' => 'Invalid action'], 400);
    }
}

// =====================================================
// POST METHODS
// =====================================================

if ($method === 'POST') {
    switch ($action) {
        case 'createCard':
            $required = ['userId', 'storeId', 'programId'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    respond(['ok' => false, 'msg' => "{$field} is required"], 400);
                }
            }
            
            // Check if user already has card for this store
            $existing = selectDB("customer_cards", 
                "`userId` = '{$input['userId']}' AND `storeId` = '{$input['storeId']}' AND `status` = '0'");
            if ($existing) {
                respond(['ok' => false, 'msg' => 'Card already exists for this store'], 409);
            }
            
            // Get program details for welcome bonus
            $program = selectDB("loyalty_programs", "`id` = '{$input['programId']}' AND `status` = '0'");
            if ($program && $program[0]['welcomeBonus']) {
                $input['welcomeBonus'] = $program[0]['welcomeBonus'];
            }
            
            $result = createCustomerCard($input);
            respond($result, $result['ok'] ? 201 : 400);
            break;
            
        case 'addPoints':
            $required = ['cardId', 'userId', 'storeId', 'points'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    respond(['ok' => false, 'msg' => "{$field} is required"], 400);
                }
            }
            
            $result = addPoints($input);
            respond($result, $result['ok'] ? 200 : 400);
            break;
            
        case 'redeemPoints':
            $required = ['cardId', 'points'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    respond(['ok' => false, 'msg' => "{$field} is required"], 400);
                }
            }
            
            $result = redeemPoints($input);
            respond($result, $result['ok'] ? 200 : 400);
            break;
            
        case 'addStamps':
            $required = ['cardId'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    respond(['ok' => false, 'msg' => "{$field} is required"], 400);
                }
            }
            
            $result = addStamps($input);
            respond($result, $result['ok'] ? 200 : 400);
            break;
            
        case 'toggleFavorite':
            $cardId = $input['cardId'] ?? null;
            if (!$cardId) respond(['ok' => false, 'msg' => 'Card ID required'], 400);
            
            $card = selectDB("customer_cards", "`id` = '{$cardId}' AND `status` = '0'");
            if ($card) {
                $newValue = $card[0]['favorited'] ? 0 : 1;
                $sql = "UPDATE `customer_cards` SET `favorited` = ? WHERE `id` = ?";
                if ($stmt = $dbconnect->prepare($sql)) {
                    $stmt->bind_param("ii", $newValue, $cardId);
                    $stmt->execute();
                    $stmt->close();
                    respond(['ok' => true, 'favorited' => $newValue]);
                }
            }
            respond(['ok' => false, 'msg' => 'Card not found'], 404);
            break;
            
        case 'processTransaction':
            // Merchant adds points based on purchase
            $required = ['cardId', 'purchaseAmount', 'staffId'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    respond(['ok' => false, 'msg' => "{$field} is required"], 400);
                }
            }
            
            $card = selectDB("customer_cards", "`id` = '{$input['cardId']}' AND `status` = '0'");
            if (!$card) respond(['ok' => false, 'msg' => 'Card not found'], 404);
            
            $card = $card[0];
            
            // Calculate points
            $points = calculatePoints($input['purchaseAmount'], $card['programId']);
            
            // Get program to check if stamps-based
            $program = selectDB("loyalty_programs", "`id` = '{$card['programId']}'");
            
            if ($program && $program[0]['programType'] == 2) {
                // Stamps program
                $stampsToAdd = 1;
                if (isset($program[0]['stampValue']) && $program[0]['stampValue'] > 0) {
                    $stampsToAdd = floor($input['purchaseAmount'] / $program[0]['stampValue']);
                }
                
                $result = addStamps([
                    'cardId' => $input['cardId'],
                    'stamps' => $stampsToAdd,
                    'purchaseAmount' => $input['purchaseAmount'],
                    'receiptNumber' => $input['receiptNumber'] ?? null,
                    'staffId' => $input['staffId'],
                    'branchId' => $input['branchId'] ?? null
                ]);
            } else {
                // Points program
                $result = addPoints([
                    'cardId' => $input['cardId'],
                    'userId' => $card['userId'],
                    'storeId' => $card['storeId'],
                    'points' => $points,
                    'purchaseAmount' => $input['purchaseAmount'],
                    'receiptNumber' => $input['receiptNumber'] ?? null,
                    'staffId' => $input['staffId'],
                    'branchId' => $input['branchId'] ?? null
                ]);
            }
            
            respond($result, $result['ok'] ? 200 : 400);
            break;
            
        default:
            respond(['ok' => false, 'msg' => 'Invalid action'], 400);
    }
}

// =====================================================
// PUT/DELETE METHODS
// =====================================================

if ($method === 'PUT' || $method === 'DELETE') {
    respond(['ok' => false, 'msg' => 'Method not implemented'], 501);
}

// Default response
respond(['ok' => false, 'msg' => 'Invalid request'], 400);
?>
