<?php
/**
 * Stores API Endpoint
 * Handles store information, settings, and management
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

// Search stores
if ($action === 'search' && $method === 'GET') {
    $query = $_GET['q'] ?? '';
    $categoryId = $_GET['categoryId'] ?? '';
    $lat = $_GET['lat'] ?? '';
    $lng = $_GET['lng'] ?? '';
    $radius = $_GET['radius'] ?? 10; // km
    $limit = $_GET['limit'] ?? 20;
    
    $sql = "SELECT s.*, c.enTitle as categoryName, c.arTitle as categoryNameAr
            FROM stores s
            LEFT JOIN categories c ON s.categoryId = c.id
            WHERE s.status = '0' AND s.hidden = '1' AND s.isApproved = '1'";
    
    if (!empty($query)) {
        $searchTerm = $conn->real_escape_string($query);
        $sql .= " AND (s.enStoreName LIKE '%$searchTerm%' OR s.arStoreName LIKE '%$searchTerm%')";
    }
    
    if (!empty($categoryId)) {
        $sql .= " AND s.categoryId = '$categoryId'";
    }
    
    // Location-based search
    if (!empty($lat) && !empty($lng)) {
        $sql = "SELECT s.*, c.enTitle as categoryName, c.arTitle as categoryNameAr,
                (6371 * acos(cos(radians($lat)) * cos(radians(s.latitude)) * 
                cos(radians(s.longitude) - radians($lng)) + sin(radians($lat)) * 
                sin(radians(s.latitude)))) AS distance
                FROM stores s
                LEFT JOIN categories c ON s.categoryId = c.id
                WHERE s.status = '0' AND s.hidden = '1' AND s.isApproved = '1'";
        
        if (!empty($query)) {
            $sql .= " AND (s.enStoreName LIKE '%$searchTerm%' OR s.arStoreName LIKE '%$searchTerm%')";
        }
        
        if (!empty($categoryId)) {
            $sql .= " AND s.categoryId = '$categoryId'";
        }
        
        $sql .= " HAVING distance < $radius ORDER BY distance";
    } else {
        $sql .= " ORDER BY s.date DESC";
    }
    
    $sql .= " LIMIT $limit";
    
    $result = selectDB("stores s", "1=1", $sql);
    
    if ($result && is_array($result)) {
        respond(true, $result);
    } else {
        respond(true, []);
    }
}

// Get store details
if ($action === 'getDetails' && $method === 'GET') {
    $storeId = $_GET['storeId'] ?? '';
    
    if (empty($storeId)) {
        respond(false, null, 'Store ID required');
    }
    
    $storeResult = selectDB("stores s
                             LEFT JOIN categories c ON s.categoryId = c.id",
                            "s.id = '$storeId' AND s.status = '0' LIMIT 1");
    
    if (!$storeResult || !is_array($storeResult) || count($storeResult) == 0) {
        respond(false, null, 'Store not found');
    }
    
    $store = $storeResult[0];
    
    // Get loyalty programs
    $programsResult = selectDB("loyalty_programs", "`storeId`='$storeId' AND `status`='0'");
    $store['programs'] = ($programsResult && is_array($programsResult)) ? $programsResult : [];
    
    // Get rewards catalog
    $rewardsResult = selectDB("rewards_catalog", "`storeId`='$storeId' AND `status`='0' AND `hidden`='1' ORDER BY pointsCost ASC");
    $store['rewards'] = ($rewardsResult && is_array($rewardsResult)) ? $rewardsResult : [];
    
    // Get statistics
    $stats = [];
    
    // Total members
    $membersQuery = "SELECT COUNT(DISTINCT cc.customerId) as count
                     FROM customer_cards cc
                     JOIN loyalty_programs lp ON cc.programId = lp.id
                     WHERE lp.storeId = '$storeId' AND cc.status = '0'";
    $membersResult = selectDB("customer_cards cc", "1=1", $membersQuery);
    $stats['totalMembers'] = ($membersResult && is_array($membersResult)) ? intval($membersResult[0]['count']) : 0;
    
    // Total active cards
    $cardsQuery = "SELECT COUNT(*) as count
                   FROM customer_cards cc
                   JOIN loyalty_programs lp ON cc.programId = lp.id
                   WHERE lp.storeId = '$storeId' AND cc.status = '0'";
    $cardsResult = selectDB("customer_cards cc", "1=1", $cardsQuery);
    $stats['totalCards'] = ($cardsResult && is_array($cardsResult)) ? intval($cardsResult[0]['count']) : 0;
    
    // Total rewards redeemed
    $redemptionsQuery = "SELECT COUNT(*) as count
                         FROM redemptions r
                         JOIN rewards_catalog rc ON r.rewardId = rc.id
                         WHERE rc.storeId = '$storeId' AND r.status = 'completed'";
    $redemptionsResult = selectDB("redemptions r", "1=1", $redemptionsQuery);
    $stats['totalRedemptions'] = ($redemptionsResult && is_array($redemptionsResult)) ? intval($redemptionsResult[0]['count']) : 0;
    
    $store['stats'] = $stats;
    
    respond(true, $store);
}

// Get nearby stores
if ($action === 'getNearby' && $method === 'GET') {
    $lat = $_GET['lat'] ?? '';
    $lng = $_GET['lng'] ?? '';
    $radius = $_GET['radius'] ?? 5; // km
    $limit = $_GET['limit'] ?? 10;
    
    if (empty($lat) || empty($lng)) {
        respond(false, null, 'Location coordinates required');
    }
    
    $sql = "SELECT s.*, c.enTitle as categoryName, c.arTitle as categoryNameAr,
            (6371 * acos(cos(radians($lat)) * cos(radians(s.latitude)) * 
            cos(radians(s.longitude) - radians($lng)) + sin(radians($lat)) * 
            sin(radians(s.latitude)))) AS distance
            FROM stores s
            LEFT JOIN categories c ON s.categoryId = c.id
            WHERE s.status = '0' AND s.hidden = '1' AND s.isApproved = '1'
            AND s.latitude IS NOT NULL AND s.longitude IS NOT NULL
            HAVING distance < $radius
            ORDER BY distance
            LIMIT $limit";
    
    $result = selectDB("stores s", "1=1", $sql);
    
    if ($result && is_array($result)) {
        respond(true, $result);
    } else {
        respond(true, []);
    }
}

// Update store basic info
if ($action === 'updateBasicInfo' && $method === 'POST') {
    $storeId = $_POST['storeId'] ?? '';
    $enStoreName = $conn->real_escape_string($_POST['enStoreName'] ?? '');
    $arStoreName = $conn->real_escape_string($_POST['arStoreName'] ?? '');
    $enDescription = $conn->real_escape_string($_POST['enDescription'] ?? '');
    $arDescription = $conn->real_escape_string($_POST['arDescription'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $website = $conn->real_escape_string($_POST['website'] ?? '');
    
    if (empty($storeId)) {
        respond(false, null, 'Store ID required');
    }
    
    $updateQuery = "UPDATE stores SET
                    enStoreName = '$enStoreName',
                    arStoreName = '$arStoreName',
                    enDescription = '$enDescription',
                    arDescription = '$arDescription',
                    phone = '$phone',
                    email = '$email',
                    website = '$website'
                    WHERE id = '$storeId'";
    
    if ($conn->query($updateQuery)) {
        respond(true, null, 'Store information updated successfully');
    } else {
        respond(false, null, 'Failed to update store information');
    }
}

// Update store branding
if ($action === 'updateBranding' && $method === 'POST') {
    $storeId = $_POST['storeId'] ?? '';
    $brandColor = $conn->real_escape_string($_POST['brandColor'] ?? '');
    
    if (empty($storeId)) {
        respond(false, null, 'Store ID required');
    }
    
    $updateFields = [];
    
    if (!empty($brandColor)) {
        $updateFields[] = "brandColor = '$brandColor'";
    }
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoName = time() . '_' . basename($_FILES['logo']['name']);
        $logoPath = '../logos/' . $logoName;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
            $updateFields[] = "logo = '$logoName'";
        }
    }
    
    // Handle cover image upload
    if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] === UPLOAD_ERR_OK) {
        $coverName = time() . '_cover_' . basename($_FILES['coverImage']['name']);
        $coverPath = '../logos/' . $coverName;
        
        if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $coverPath)) {
            $updateFields[] = "coverImage = '$coverName'";
        }
    }
    
    if (empty($updateFields)) {
        respond(false, null, 'No branding updates provided');
    }
    
    $updateQuery = "UPDATE stores SET " . implode(', ', $updateFields) . " WHERE id = '$storeId'";
    
    if ($conn->query($updateQuery)) {
        respond(true, null, 'Branding updated successfully');
    } else {
        respond(false, null, 'Failed to update branding');
    }
}

// Get store staff
if ($action === 'getStaff' && $method === 'GET') {
    $storeId = $_GET['storeId'] ?? '';
    
    if (empty($storeId)) {
        respond(false, null, 'Store ID required');
    }
    
    $staffResult = selectDB("store_staff ss
                             JOIN employees e ON ss.employeeId = e.id",
                            "ss.storeId = '$storeId' AND ss.status = '0'");
    
    if ($staffResult && is_array($staffResult)) {
        respond(true, $staffResult);
    } else {
        respond(true, []);
    }
}

// Add staff member
if ($action === 'addStaff' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $storeId = $conn->real_escape_string($data['storeId'] ?? '');
    $employeeId = $conn->real_escape_string($data['employeeId'] ?? '');
    $role = $conn->real_escape_string($data['role'] ?? 'cashier');
    
    if (empty($storeId) || empty($employeeId)) {
        respond(false, null, 'Store ID and Employee ID required');
    }
    
    // Check if already exists
    $existingResult = selectDB("store_staff", "`storeId`='$storeId' AND `employeeId`='$employeeId' LIMIT 1");
    if ($existingResult && is_array($existingResult) && count($existingResult) > 0) {
        respond(false, null, 'Employee already assigned to this store');
    }
    
    $insertQuery = "INSERT INTO store_staff (storeId, employeeId, role, date, status, hidden)
                    VALUES ('$storeId', '$employeeId', '$role', NOW(), '0', '1')";
    
    if ($conn->query($insertQuery)) {
        respond(true, null, 'Staff member added successfully');
    } else {
        respond(false, null, 'Failed to add staff member');
    }
}

// Remove staff member
if ($action === 'removeStaff' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $staffId = $conn->real_escape_string($data['staffId'] ?? '');
    
    if (empty($staffId)) {
        respond(false, null, 'Staff ID required');
    }
    
    $updateQuery = "UPDATE store_staff SET status = '1' WHERE id = '$staffId'";
    
    if ($conn->query($updateQuery)) {
        respond(true, null, 'Staff member removed successfully');
    } else {
        respond(false, null, 'Failed to remove staff member');
    }
}

// Get store categories
if ($action === 'getCategories' && $method === 'GET') {
    $categoriesResult = selectDB("categories", "`status`='0' AND `hidden`='1' ORDER BY enTitle ASC");
    
    if ($categoriesResult && is_array($categoriesResult)) {
        respond(true, $categoriesResult);
    } else {
        respond(true, []);
    }
}

respond(false, null, 'Invalid action');
?>
