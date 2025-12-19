<?php
/**
 * Customers API Endpoint
 * Handles customer search, profile retrieval, and statistics
 */

header('Content-Type: application/json');
require_once '../dashboard/includes/config.php';
require_once '../dashboard/includes/functions.php';

// Get request data
$action = $_GET['action'] ?? $_POST['action'] ?? 'search';
$method = $_SERVER['REQUEST_METHOD'];

// Response helper
function respond($ok, $data = null, $msg = '') {
    echo json_encode(['ok' => $ok, 'data' => $data, 'msg' => $msg]);
    exit;
}

// Search customers
if ($action === 'search' && $method === 'GET') {
    $query = $_GET['q'] ?? '';
    $storeId = $_GET['storeId'] ?? '';
    
    if (empty($query) || strlen($query) < 2) {
        respond(false, null, 'Search query too short');
    }
    
    $searchTerm = $conn->real_escape_string($query);
    
    // Build search query
    $sql = "SELECT DISTINCT u.id, u.firstName, u.lastName, u.phone, u.email, u.date
            FROM users u";
    
    if (!empty($storeId)) {
        $sql .= " INNER JOIN customer_cards cc ON u.id = cc.customerId
                  INNER JOIN loyalty_programs lp ON cc.programId = lp.id
                  WHERE lp.storeId = '$storeId' AND";
    } else {
        $sql .= " WHERE";
    }
    
    $sql .= " (u.firstName LIKE '%$searchTerm%' 
              OR u.lastName LIKE '%$searchTerm%' 
              OR u.phone LIKE '%$searchTerm%'
              OR u.email LIKE '%$searchTerm%')
              AND u.status = '0'
              ORDER BY u.date DESC
              LIMIT 20";
    
    $result = selectDB("users u", "1=1", $sql);
    
    if ($result && is_array($result)) {
        respond(true, $result);
    } else {
        respond(true, []);
    }
}

// Get customer details
if ($action === 'getDetails' && $method === 'GET') {
    $customerId = $_GET['customerId'] ?? '';
    
    if (empty($customerId)) {
        respond(false, null, 'Customer ID required');
    }
    
    // Get customer info
    $customerResult = selectDB("users", "`id`='$customerId' AND `status`='0' LIMIT 1");
    
    if (!$customerResult || !is_array($customerResult) || count($customerResult) == 0) {
        respond(false, null, 'Customer not found');
    }
    
    $customer = $customerResult[0];
    
    // Get customer cards
    $cardsResult = selectDB("customer_cards cc
                             JOIN loyalty_programs lp ON cc.programId = lp.id
                             JOIN stores s ON lp.storeId = s.id",
                            "cc.customerId = '$customerId' AND cc.status = '0' AND lp.status = '0'");
    
    $customer['cards'] = ($cardsResult && is_array($cardsResult)) ? $cardsResult : [];
    
    // Get total points across all cards
    $totalPoints = 0;
    foreach ($customer['cards'] as $card) {
        $totalPoints += intval($card['balance'] ?? 0);
    }
    $customer['totalPoints'] = $totalPoints;
    
    // Get total rewards redeemed
    $redemptionsResult = selectDB("redemptions", "customerId = '$customerId' AND status = 'completed'");
    $customer['totalRedemptions'] = is_array($redemptionsResult) ? count($redemptionsResult) : 0;
    
    // Get recent transactions (last 10)
    $transactionsResult = selectDB("points_transactions pt
                                    JOIN customer_cards cc ON pt.cardId = cc.id
                                    JOIN loyalty_programs lp ON cc.programId = lp.id
                                    JOIN stores s ON lp.storeId = s.id",
                                   "pt.customerId = '$customerId' AND pt.status = '0'
                                    ORDER BY pt.date DESC LIMIT 10");
    
    $customer['recentTransactions'] = ($transactionsResult && is_array($transactionsResult)) ? $transactionsResult : [];
    
    respond(true, $customer);
}

// Get customer statistics
if ($action === 'getStats' && $method === 'GET') {
    $customerId = $_GET['customerId'] ?? '';
    $storeId = $_GET['storeId'] ?? '';
    
    if (empty($customerId)) {
        respond(false, null, 'Customer ID required');
    }
    
    $stats = [];
    
    // Total cards
    $cardsQuery = "SELECT COUNT(*) as count FROM customer_cards WHERE customerId = '$customerId' AND status = '0'";
    if (!empty($storeId)) {
        $cardsQuery .= " AND programId IN (SELECT id FROM loyalty_programs WHERE storeId = '$storeId')";
    }
    $cardsResult = selectDB("customer_cards", "1=1", $cardsQuery);
    $stats['totalCards'] = ($cardsResult && is_array($cardsResult)) ? intval($cardsResult[0]['count']) : 0;
    
    // Total points
    $pointsQuery = "SELECT SUM(balance) as total FROM customer_cards WHERE customerId = '$customerId' AND status = '0'";
    if (!empty($storeId)) {
        $pointsQuery .= " AND programId IN (SELECT id FROM loyalty_programs WHERE storeId = '$storeId')";
    }
    $pointsResult = selectDB("customer_cards", "1=1", $pointsQuery);
    $stats['totalPoints'] = ($pointsResult && is_array($pointsResult)) ? intval($pointsResult[0]['total'] ?? 0) : 0;
    
    // Total visits (transactions)
    $visitsQuery = "SELECT COUNT(*) as count FROM points_transactions WHERE customerId = '$customerId' AND type = 'earned' AND status = '0'";
    if (!empty($storeId)) {
        $visitsQuery .= " AND cardId IN (SELECT cc.id FROM customer_cards cc 
                         JOIN loyalty_programs lp ON cc.programId = lp.id 
                         WHERE lp.storeId = '$storeId')";
    }
    $visitsResult = selectDB("points_transactions", "1=1", $visitsQuery);
    $stats['totalVisits'] = ($visitsResult && is_array($visitsResult)) ? intval($visitsResult[0]['count']) : 0;
    
    // Total spent
    $spentQuery = "SELECT SUM(amount) as total FROM points_transactions WHERE customerId = '$customerId' AND type = 'earned' AND status = '0'";
    if (!empty($storeId)) {
        $spentQuery .= " AND cardId IN (SELECT cc.id FROM customer_cards cc 
                        JOIN loyalty_programs lp ON cc.programId = lp.id 
                        WHERE lp.storeId = '$storeId')";
    }
    $spentResult = selectDB("points_transactions", "1=1", $spentQuery);
    $stats['totalSpent'] = ($spentResult && is_array($spentResult)) ? floatval($spentResult[0]['total'] ?? 0) : 0;
    
    // Total redemptions
    $redemptionsQuery = "SELECT COUNT(*) as count FROM redemptions WHERE customerId = '$customerId' AND status = 'completed'";
    if (!empty($storeId)) {
        $redemptionsQuery .= " AND rewardId IN (SELECT id FROM rewards_catalog WHERE storeId = '$storeId')";
    }
    $redemptionsResult = selectDB("redemptions", "1=1", $redemptionsQuery);
    $stats['totalRedemptions'] = ($redemptionsResult && is_array($redemptionsResult)) ? intval($redemptionsResult[0]['count']) : 0;
    
    // Average transaction
    if ($stats['totalVisits'] > 0) {
        $stats['avgTransaction'] = round($stats['totalSpent'] / $stats['totalVisits'], 2);
    } else {
        $stats['avgTransaction'] = 0;
    }
    
    // Last visit date
    $lastVisitQuery = "SELECT MAX(date) as lastDate FROM points_transactions 
                       WHERE customerId = '$customerId' AND type = 'earned' AND status = '0'";
    if (!empty($storeId)) {
        $lastVisitQuery .= " AND cardId IN (SELECT cc.id FROM customer_cards cc 
                           JOIN loyalty_programs lp ON cc.programId = lp.id 
                           WHERE lp.storeId = '$storeId')";
    }
    $lastVisitResult = selectDB("points_transactions", "1=1", $lastVisitQuery);
    $stats['lastVisit'] = ($lastVisitResult && is_array($lastVisitResult)) ? $lastVisitResult[0]['lastDate'] : null;
    
    respond(true, $stats);
}

// Get customer cards for a specific store
if ($action === 'getCards' && $method === 'GET') {
    $customerId = $_GET['customerId'] ?? '';
    $storeId = $_GET['storeId'] ?? '';
    
    if (empty($customerId) || empty($storeId)) {
        respond(false, null, 'Customer ID and Store ID required');
    }
    
    $cardsResult = selectDB("customer_cards cc
                             JOIN loyalty_programs lp ON cc.programId = lp.id
                             JOIN card_templates ct ON cc.templateId = ct.id",
                            "cc.customerId = '$customerId' 
                             AND lp.storeId = '$storeId' 
                             AND cc.status = '0'
                             AND lp.status = '0'");
    
    if ($cardsResult && is_array($cardsResult)) {
        respond(true, $cardsResult);
    } else {
        respond(true, []);
    }
}

// Get customer activity timeline
if ($action === 'getActivity' && $method === 'GET') {
    $customerId = $_GET['customerId'] ?? '';
    $storeId = $_GET['storeId'] ?? '';
    $limit = $_GET['limit'] ?? 20;
    
    if (empty($customerId)) {
        respond(false, null, 'Customer ID required');
    }
    
    $activities = [];
    
    // Get points transactions
    $pointsQuery = "SELECT pt.*, 'points' as activityType, pt.points as value, 
                    s.enStoreName, s.arStoreName
                    FROM points_transactions pt
                    JOIN customer_cards cc ON pt.cardId = cc.id
                    JOIN loyalty_programs lp ON cc.programId = lp.id
                    JOIN stores s ON lp.storeId = s.id
                    WHERE pt.customerId = '$customerId' AND pt.status = '0'";
    
    if (!empty($storeId)) {
        $pointsQuery .= " AND lp.storeId = '$storeId'";
    }
    
    $pointsResult = selectDB("points_transactions pt", "1=1", $pointsQuery);
    if ($pointsResult && is_array($pointsResult)) {
        $activities = array_merge($activities, $pointsResult);
    }
    
    // Get redemptions
    $redemptionsQuery = "SELECT r.*, 'redemption' as activityType, r.pointsCost as value,
                         rc.enTitle as rewardName, rc.arTitle as rewardNameAr,
                         s.enStoreName, s.arStoreName
                         FROM redemptions r
                         JOIN rewards_catalog rc ON r.rewardId = rc.id
                         JOIN stores s ON rc.storeId = s.id
                         WHERE r.customerId = '$customerId'";
    
    if (!empty($storeId)) {
        $redemptionsQuery .= " AND rc.storeId = '$storeId'";
    }
    
    $redemptionsResult = selectDB("redemptions r", "1=1", $redemptionsQuery);
    if ($redemptionsResult && is_array($redemptionsResult)) {
        $activities = array_merge($activities, $redemptionsResult);
    }
    
    // Sort by date
    usort($activities, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    // Limit results
    $activities = array_slice($activities, 0, $limit);
    
    respond(true, $activities);
}

// Create new customer
if ($action === 'create' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $firstName = $conn->real_escape_string($data['firstName'] ?? '');
    $lastName = $conn->real_escape_string($data['lastName'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    
    if (empty($firstName) || empty($phone)) {
        respond(false, null, 'First name and phone are required');
    }
    
    // Check if phone already exists
    $existingResult = selectDB("users", "`phone`='$phone' LIMIT 1");
    if ($existingResult && is_array($existingResult) && count($existingResult) > 0) {
        respond(false, null, 'Phone number already registered');
    }
    
    // Insert new customer
    $insertQuery = "INSERT INTO users (firstName, lastName, phone, email, date, status, hidden) 
                    VALUES ('$firstName', '$lastName', '$phone', '$email', NOW(), '0', '1')";
    
    if ($conn->query($insertQuery)) {
        $customerId = $conn->insert_id;
        respond(true, ['customerId' => $customerId], 'Customer created successfully');
    } else {
        respond(false, null, 'Failed to create customer');
    }
}

// Update customer profile
if ($action === 'update' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $customerId = $conn->real_escape_string($data['customerId'] ?? '');
    $firstName = $conn->real_escape_string($data['firstName'] ?? '');
    $lastName = $conn->real_escape_string($data['lastName'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    
    if (empty($customerId)) {
        respond(false, null, 'Customer ID required');
    }
    
    $updateQuery = "UPDATE users SET 
                    firstName = '$firstName',
                    lastName = '$lastName',
                    email = '$email'
                    WHERE id = '$customerId'";
    
    if ($conn->query($updateQuery)) {
        respond(true, null, 'Profile updated successfully');
    } else {
        respond(false, null, 'Failed to update profile');
    }
}

respond(false, null, 'Invalid action');
?>
