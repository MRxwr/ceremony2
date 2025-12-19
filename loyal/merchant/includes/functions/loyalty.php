<?php
// =====================================================
// LOYALTY WALLET PLATFORM - CORE FUNCTIONS
// =====================================================

// =====================================================
// CUSTOMER CARD MANAGEMENT
// =====================================================

// Generate unique card number
function generateCardNumber($storeId) {
    $prefix = str_pad($storeId, 4, '0', STR_PAD_LEFT);
    $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    $timestamp = substr(time(), -4);
    return $prefix . '-' . $random . '-' . $timestamp;
}

// Create new customer loyalty card
function createCustomerCard($data) {
    GLOBAL $dbconnect;
    
    $cardNumber = generateCardNumber($data['storeId']);
    $qrData = encryptQRData(json_encode([
        'type' => 'loyalty_card',
        'cardNumber' => $cardNumber,
        'userId' => $data['userId'],
        'storeId' => $data['storeId'],
        'timestamp' => time()
    ]));
    
    $barcode = generateBarcode($cardNumber);
    $issuedDate = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO `customer_cards` 
            (`cardNumber`, `userId`, `storeId`, `programId`, `qrCode`, `barcode`, `issuedDate`, `cardStatus`, `hidden`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1, 0)";
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("siiisss", 
            $cardNumber, 
            $data['userId'], 
            $data['storeId'], 
            $data['programId'],
            $qrData,
            $barcode,
            $issuedDate
        );
        
        if ($stmt->execute()) {
            $cardId = $stmt->insert_id;
            $stmt->close();
            
            // Add welcome bonus if configured
            if (isset($data['welcomeBonus']) && $data['welcomeBonus'] > 0) {
                addPoints([
                    'cardId' => $cardId,
                    'userId' => $data['userId'],
                    'storeId' => $data['storeId'],
                    'points' => $data['welcomeBonus'],
                    'transactionType' => 6, // bonus
                    'description' => 'Welcome bonus'
                ]);
            }
            
            return ['ok' => true, 'cardId' => $cardId, 'cardNumber' => $cardNumber];
        }
        $stmt->close();
    }
    return ['ok' => false, 'msg' => 'Failed to create card'];
}

// Get user's wallet cards
function getUserWalletCards($userId) {
    $cards = selectDB("customer_cards", "`userId` = '{$userId}' AND `status` = '0' AND `cardStatus` = '1' ORDER BY `favorited` DESC, `walletOrder` ASC, `lastActivityDate` DESC");
    
    if ($cards) {
        for ($i = 0; $i < count($cards); $i++) {
            // Get store info
            $store = selectDB("stores", "`id` = '{$cards[$i]['storeId']}' AND `status` = '0'");
            if ($store) {
                $cards[$i]['store'] = $store[0];
            }
            
            // Get program info
            $program = selectDB("loyalty_programs", "`id` = '{$cards[$i]['programId']}' AND `status` = '0'");
            if ($program) {
                $cards[$i]['program'] = $program[0];
                
                // Get card template
                $template = selectDB("card_templates", "`programId` = '{$cards[$i]['programId']}' AND `status` = '0' LIMIT 1");
                if ($template) {
                    $cards[$i]['template'] = $template[0];
                }
            }
            
            // Check for expiring points
            $cards[$i]['expiringPoints'] = getExpiringPoints($cards[$i]['id']);
        }
        return $cards;
    }
    return [];
}

// Get single card details
function getCardDetails($cardId, $userId = null) {
    $where = "`id` = '{$cardId}' AND `status` = '0'";
    if ($userId) {
        $where .= " AND `userId` = '{$userId}'";
    }
    
    $card = selectDB("customer_cards", $where);
    if ($card) {
        $card = $card[0];
        
        // Get store
        $store = selectDB("stores", "`id` = '{$card['storeId']}' AND `status` = '0'");
        if ($store) $card['store'] = $store[0];
        
        // Get program
        $program = selectDB("loyalty_programs", "`id` = '{$card['programId']}' AND `status` = '0'");
        if ($program) $card['program'] = $program[0];
        
        // Get template
        $template = selectDB("card_templates", "`programId` = '{$card['programId']}' AND `status` = '0' LIMIT 1");
        if ($template) $card['template'] = $template[0];
        
        // Get recent transactions
        $card['recentTransactions'] = getCardTransactions($cardId, 10);
        
        return $card;
    }
    return null;
}

// Get card by QR code
function getCardByQR($qrCode) {
    $decrypted = decryptQRData($qrCode);
    if ($decrypted) {
        $data = json_decode($decrypted, true);
        if ($data && isset($data['cardNumber'])) {
            $card = selectDB("customer_cards", "`cardNumber` = '{$data['cardNumber']}' AND `status` = '0'");
            if ($card) {
                return getCardDetails($card[0]['id']);
            }
        }
    }
    return null;
}

// =====================================================
// POINTS MANAGEMENT
// =====================================================

// Add points to card
function addPoints($data) {
    GLOBAL $dbconnect;
    
    // Get current balance
    $card = selectDB("customer_cards", "`id` = '{$data['cardId']}' AND `status` = '0'");
    if (!$card) return ['ok' => false, 'msg' => 'Card not found'];
    
    $card = $card[0];
    $newBalance = $card['currentPoints'] + $data['points'];
    
    // Calculate expiry date
    $program = selectDB("loyalty_programs", "`id` = '{$card['programId']}'");
    $expiryDate = null;
    if ($program && $program[0]['pointsExpiry']) {
        $expiryDate = date('Y-m-d H:i:s', strtotime('+' . $program[0]['pointsExpiry'] . ' days'));
    }
    
    // Insert transaction
    $transactionType = $data['transactionType'] ?? 1; // 1=earned
    $description = $data['description'] ?? 'Points earned';
    $purchaseAmount = $data['purchaseAmount'] ?? null;
    $receiptNumber = $data['receiptNumber'] ?? null;
    $staffId = $data['staffId'] ?? null;
    $branchId = $data['branchId'] ?? null;
    
    $sql = "INSERT INTO `points_transactions` 
            (`cardId`, `userId`, `storeId`, `branchId`, `staffId`, `transactionType`, `points`, `pointsBalance`, 
             `purchaseAmount`, `receiptNumber`, `description`, `expiryDate`, `hidden`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("iiiiiiiissss",
            $data['cardId'],
            $data['userId'],
            $data['storeId'],
            $branchId,
            $staffId,
            $transactionType,
            $data['points'],
            $newBalance,
            $purchaseAmount,
            $receiptNumber,
            $description,
            $expiryDate
        );
        
        if ($stmt->execute()) {
            $transactionId = $stmt->insert_id;
            $stmt->close();
            
            // Update card balance
            $updateSql = "UPDATE `customer_cards` SET 
                         `currentPoints` = ?,
                         `lifetimePoints` = `lifetimePoints` + ?,
                         `lastActivityDate` = NOW()
                         WHERE `id` = ?";
            
            if ($updateStmt = $dbconnect->prepare($updateSql)) {
                $updateStmt->bind_param("iii", $newBalance, $data['points'], $data['cardId']);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            // Send notification
            sendPointsNotification($data['userId'], $data['storeId'], $data['points'], 'earned');
            
            // Check for achievements
            checkAchievements($data['userId'], $data['storeId'], 'points', $newBalance);
            
            return ['ok' => true, 'transactionId' => $transactionId, 'newBalance' => $newBalance];
        }
        $stmt->close();
    }
    
    return ['ok' => false, 'msg' => 'Failed to add points'];
}

// Redeem points
function redeemPoints($data) {
    GLOBAL $dbconnect;
    
    // Validate card has enough points
    $card = selectDB("customer_cards", "`id` = '{$data['cardId']}' AND `status` = '0'");
    if (!$card) return ['ok' => false, 'msg' => 'Card not found'];
    
    $card = $card[0];
    if ($card['currentPoints'] < $data['points']) {
        return ['ok' => false, 'msg' => 'Insufficient points'];
    }
    
    $newBalance = $card['currentPoints'] - $data['points'];
    
    // Create transaction
    $result = addPoints([
        'cardId' => $data['cardId'],
        'userId' => $card['userId'],
        'storeId' => $card['storeId'],
        'points' => -$data['points'], // negative for redemption
        'transactionType' => 2, // redeemed
        'description' => $data['description'] ?? 'Points redeemed',
        'redemptionId' => $data['redemptionId'] ?? null,
        'staffId' => $data['staffId'] ?? null
    ]);
    
    if ($result['ok']) {
        // Update redeemed points counter
        $updateSql = "UPDATE `customer_cards` SET `redeemedPoints` = `redeemedPoints` + ? WHERE `id` = ?";
        if ($stmt = $dbconnect->prepare($updateSql)) {
            $stmt->bind_param("ii", $data['points'], $data['cardId']);
            $stmt->execute();
            $stmt->close();
        }
        
        sendPointsNotification($card['userId'], $card['storeId'], $data['points'], 'redeemed');
    }
    
    return $result;
}

// Calculate points from purchase amount
function calculatePoints($purchaseAmount, $programId) {
    $program = selectDB("loyalty_programs", "`id` = '{$programId}' AND `status` = '0'");
    if ($program && $program[0]['pointsPerCurrency']) {
        $points = floor($purchaseAmount * $program[0]['pointsPerCurrency']);
        return $points;
    }
    return 0;
}

// Get expiring points
function getExpiringPoints($cardId, $days = 30) {
    $futureDate = date('Y-m-d H:i:s', strtotime("+{$days} days"));
    $transactions = selectDB("points_transactions", 
        "`cardId` = '{$cardId}' AND `transactionType` = '1' AND `expiryDate` IS NOT NULL 
         AND `expiryDate` <= '{$futureDate}' AND `status` = '0'");
    
    $expiringPoints = 0;
    if ($transactions) {
        foreach ($transactions as $trans) {
            $expiringPoints += $trans['points'];
        }
    }
    return $expiringPoints;
}

// =====================================================
// STAMPS MANAGEMENT
// =====================================================

// Add stamps to card
function addStamps($data) {
    GLOBAL $dbconnect;
    
    $card = selectDB("customer_cards", "`id` = '{$data['cardId']}' AND `status` = '0'");
    if (!$card) return ['ok' => false, 'msg' => 'Card not found'];
    
    $card = $card[0];
    $program = selectDB("loyalty_programs", "`id` = '{$card['programId']}' AND `status` = '0'");
    if (!$program) return ['ok' => false, 'msg' => 'Program not found'];
    
    $program = $program[0];
    $stampsAdded = $data['stamps'] ?? 1;
    $newBalance = $card['currentStamps'] + $stampsAdded;
    $cardCompleted = false;
    
    // Check if card is completed
    if ($program['stampsRequired'] && $newBalance >= $program['stampsRequired']) {
        $cardCompleted = true;
        $newBalance = $newBalance - $program['stampsRequired'];
    }
    
    // Insert stamp transaction
    $transactionType = $data['transactionType'] ?? 1; // 1=added
    if ($cardCompleted) $transactionType = 3; // completed_card
    
    $sql = "INSERT INTO `stamps_transactions` 
            (`cardId`, `userId`, `storeId`, `branchId`, `staffId`, `transactionType`, 
             `stampsAdded`, `stampsBalance`, `purchaseAmount`, `receiptNumber`, `description`, `hidden`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
    
    $description = $data['description'] ?? 'Stamps added';
    $purchaseAmount = $data['purchaseAmount'] ?? null;
    $receiptNumber = $data['receiptNumber'] ?? null;
    $staffId = $data['staffId'] ?? null;
    $branchId = $data['branchId'] ?? null;
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("iiiiiiiisss",
            $data['cardId'],
            $card['userId'],
            $card['storeId'],
            $branchId,
            $staffId,
            $transactionType,
            $stampsAdded,
            $newBalance,
            $purchaseAmount,
            $receiptNumber,
            $description
        );
        
        if ($stmt->execute()) {
            $transactionId = $stmt->insert_id;
            $stmt->close();
            
            // Update card
            $updateFields = "`currentStamps` = ?, `lastActivityDate` = NOW()";
            if ($cardCompleted) {
                $updateFields = "`currentStamps` = ?, `completedCards` = `completedCards` + 1, `lastActivityDate` = NOW()";
            }
            
            $updateSql = "UPDATE `customer_cards` SET {$updateFields} WHERE `id` = ?";
            if ($updateStmt = $dbconnect->prepare($updateSql)) {
                $updateStmt->bind_param("ii", $newBalance, $data['cardId']);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            // Send notification
            if ($cardCompleted) {
                sendStampNotification($card['userId'], $card['storeId'], 'completed');
            } else {
                sendStampNotification($card['userId'], $card['storeId'], 'added', $stampsAdded);
            }
            
            return [
                'ok' => true, 
                'transactionId' => $transactionId, 
                'newBalance' => $newBalance, 
                'cardCompleted' => $cardCompleted
            ];
        }
        $stmt->close();
    }
    
    return ['ok' => false, 'msg' => 'Failed to add stamps'];
}

// =====================================================
// STORES & DISCOVERY
// =====================================================

// Get all active stores
function getAllStores($filters = []) {
    $where = "`status` = '0' AND `hidden` = '1' AND `verificationStatus` = '1'";
    
    if (isset($filters['categoryId'])) {
        $where .= " AND `categoryId` = '{$filters['categoryId']}'";
    }
    
    if (isset($filters['featured']) && $filters['featured']) {
        $where .= " AND `featured` = '1'";
    }
    
    if (isset($filters['search']) && !empty($filters['search'])) {
        $search = mysqli_real_escape_string($GLOBALS['dbconnect'], $filters['search']);
        $where .= " AND (`storeName` LIKE '%{$search}%' OR `enStoreName` LIKE '%{$search}%' OR `arStoreName` LIKE '%{$search}%')";
    }
    
    $orderBy = $filters['orderBy'] ?? "`rank` ASC, `rating` DESC";
    
    return selectDB("stores", "{$where} ORDER BY {$orderBy}");
}

// Get nearby stores (with location)
function getNearbyStores($latitude, $longitude, $radius = 10) {
    GLOBAL $dbconnect;
    
    // Haversine formula for distance calculation
    $sql = "SELECT *, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude)))) AS distance 
            FROM `stores` 
            WHERE `status` = '0' AND `hidden` = '1' AND `verificationStatus` = '1'
            AND `latitude` IS NOT NULL AND `longitude` IS NOT NULL
            HAVING distance < ? 
            ORDER BY distance ASC";
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("dddd", $latitude, $longitude, $latitude, $radius);
        $stmt->execute();
        $result = $stmt->get_result();
        $stores = [];
        while ($row = $result->fetch_assoc()) {
            $stores[] = $row;
        }
        $stmt->close();
        return $stores;
    }
    return [];
}

// Get store details
function getStoreDetails($storeId) {
    $store = selectDB("stores", "`id` = '{$storeId}' AND `status` = '0'");
    if ($store) {
        $store = $store[0];
        
        // Get programs
        $store['programs'] = selectDB("loyalty_programs", "`storeId` = '{$storeId}' AND `status` = '0' AND `hidden` = '1'");
        
        // Get branches
        $store['branches'] = selectDB("store_branches", "`storeId` = '{$storeId}' AND `status` = '0' AND `hidden` = '1'");
        
        // Get rewards catalog
        $store['rewards'] = selectDB("rewards_catalog", "`storeId` = '{$storeId}' AND `status` = '0' AND `hidden` = '1' ORDER BY `rank` ASC");
        
        // Get reviews
        $store['reviews'] = selectDB("store_reviews", "`storeId` = '{$storeId}' AND `status` = '0' AND `hidden` = '1' ORDER BY `date` DESC LIMIT 10");
        
        return $store;
    }
    return null;
}

// =====================================================
// REWARDS & REDEMPTIONS
// =====================================================

// Get available rewards for card
function getAvailableRewards($cardId) {
    $card = selectDB("customer_cards", "`id` = '{$cardId}' AND `status` = '0'");
    if (!$card) return [];
    
    $card = $card[0];
    $rewards = selectDB("rewards_catalog", 
        "`storeId` = '{$card['storeId']}' AND `programId` = '{$card['programId']}' 
         AND `status` = '0' AND `hidden` = '1' 
         AND (`validFrom` IS NULL OR `validFrom` <= NOW())
         AND (`validUntil` IS NULL OR `validUntil` >= NOW())
         ORDER BY `rank` ASC");
    
    if ($rewards) {
        for ($i = 0; $i < count($rewards); $i++) {
            // Check if user can afford it
            $rewards[$i]['canAfford'] = false;
            if ($rewards[$i]['pointsCost'] && $card['currentPoints'] >= $rewards[$i]['pointsCost']) {
                $rewards[$i]['canAfford'] = true;
            }
            if ($rewards[$i]['stampsCost'] && $card['currentStamps'] >= $rewards[$i]['stampsCost']) {
                $rewards[$i]['canAfford'] = true;
            }
            
            // Check stock
            if ($rewards[$i]['stockQuantity'] !== null && $rewards[$i]['stockQuantity'] <= 0) {
                $rewards[$i]['canAfford'] = false;
                $rewards[$i]['outOfStock'] = true;
            }
        }
    }
    
    return $rewards;
}

// Create redemption
function createRedemption($data) {
    GLOBAL $dbconnect;
    
    // Validate reward and card
    $card = selectDB("customer_cards", "`id` = '{$data['cardId']}' AND `status` = '0'");
    if (!$card) return ['ok' => false, 'msg' => 'Card not found'];
    $card = $card[0];
    
    $reward = selectDB("rewards_catalog", "`id` = '{$data['rewardId']}' AND `status` = '0'");
    if (!$reward) return ['ok' => false, 'msg' => 'Reward not found'];
    $reward = $reward[0];
    
    // Validate points/stamps
    if ($reward['pointsCost'] && $card['currentPoints'] < $reward['pointsCost']) {
        return ['ok' => false, 'msg' => 'Insufficient points'];
    }
    if ($reward['stampsCost'] && $card['currentStamps'] < $reward['stampsCost']) {
        return ['ok' => false, 'msg' => 'Insufficient stamps'];
    }
    
    // Generate redemption code
    $redemptionCode = 'RDM-' . strtoupper(bin2hex(random_bytes(6)));
    
    // Generate QR for redemption
    $qrData = encryptQRData(json_encode([
        'type' => 'redemption',
        'code' => $redemptionCode,
        'cardId' => $data['cardId'],
        'rewardId' => $data['rewardId'],
        'timestamp' => time()
    ]));
    
    // Calculate expiry (default 30 days)
    $expiryDate = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Insert redemption
    $sql = "INSERT INTO `redemptions` 
            (`redemptionCode`, `cardId`, `userId`, `storeId`, `rewardId`, `pointsSpent`, `stampsSpent`, 
             `redemptionType`, `redemptionStatus`, `qrCode`, `expiryDate`, `hidden`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, 1, 0)";
    
    $redemptionType = $data['redemptionType'] ?? 1; // in_store
    $pointsSpent = $reward['pointsCost'] ?? 0;
    $stampsSpent = $reward['stampsCost'] ?? 0;
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("siiiiiiiss",
            $redemptionCode,
            $data['cardId'],
            $card['userId'],
            $card['storeId'],
            $data['rewardId'],
            $pointsSpent,
            $stampsSpent,
            $redemptionType,
            $qrData,
            $expiryDate
        );
        
        if ($stmt->execute()) {
            $redemptionId = $stmt->insert_id;
            $stmt->close();
            
            // Deduct points/stamps
            if ($pointsSpent > 0) {
                redeemPoints([
                    'cardId' => $data['cardId'],
                    'points' => $pointsSpent,
                    'redemptionId' => $redemptionId,
                    'description' => 'Redeemed: ' . direction($reward['enTitle'], $reward['arTitle'])
                ]);
            }
            
            if ($stampsSpent > 0) {
                // Deduct stamps (implement similar to points)
                $newStamps = $card['currentStamps'] - $stampsSpent;
                $updateSql = "UPDATE `customer_cards` SET `currentStamps` = ? WHERE `id` = ?";
                if ($updateStmt = $dbconnect->prepare($updateSql)) {
                    $updateStmt->bind_param("ii", $newStamps, $data['cardId']);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
            }
            
            // Update stock
            if ($reward['stockQuantity'] !== null) {
                $updateStockSql = "UPDATE `rewards_catalog` SET `stockQuantity` = `stockQuantity` - 1, `totalRedeemed` = `totalRedeemed` + 1 WHERE `id` = ?";
                if ($stockStmt = $dbconnect->prepare($updateStockSql)) {
                    $stockStmt->bind_param("i", $data['rewardId']);
                    $stockStmt->execute();
                    $stockStmt->close();
                }
            }
            
            // Send notification
            sendRedemptionNotification($card['userId'], $card['storeId'], $reward);
            
            return [
                'ok' => true, 
                'redemptionId' => $redemptionId, 
                'redemptionCode' => $redemptionCode,
                'qrCode' => $qrData
            ];
        }
        $stmt->close();
    }
    
    return ['ok' => false, 'msg' => 'Failed to create redemption'];
}

// =====================================================
// TRANSACTIONS & HISTORY
// =====================================================

// Get card transactions
function getCardTransactions($cardId, $limit = 50) {
    $points = selectDB("points_transactions", 
        "`cardId` = '{$cardId}' AND `status` = '0' ORDER BY `date` DESC LIMIT {$limit}");
    $stamps = selectDB("stamps_transactions", 
        "`cardId` = '{$cardId}' AND `status` = '0' ORDER BY `date` DESC LIMIT {$limit}");
    $visits = selectDB("visit_transactions", 
        "`cardId` = '{$cardId}' AND `status` = '0' ORDER BY `date` DESC LIMIT {$limit}");
    
    $transactions = [];
    
    if ($points) {
        foreach ($points as $trans) {
            $trans['type'] = 'points';
            $transactions[] = $trans;
        }
    }
    
    if ($stamps) {
        foreach ($stamps as $trans) {
            $trans['type'] = 'stamps';
            $transactions[] = $trans;
        }
    }
    
    if ($visits) {
        foreach ($visits as $trans) {
            $trans['type'] = 'visit';
            $transactions[] = $trans;
        }
    }
    
    // Sort by date
    usort($transactions, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    return array_slice($transactions, 0, $limit);
}

// =====================================================
// QR CODE & ENCRYPTION
// =====================================================

// Encrypt QR data
function encryptQRData($data) {
    $settings = selectDB("settings", "`id` = '1'");
    if ($settings) {
        $key = $settings[0]['cCipherKey'] ?? 'ceremony2024secretkeyforqrencryption12';
        $iv = $settings[0]['cCipherIV'] ?? 'ceremony2024iv16';
        
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($encrypted);
    }
    return null;
}

// Decrypt QR data
function decryptQRData($encryptedData) {
    $settings = selectDB("settings", "`id` = '1'");
    if ($settings) {
        $key = $settings[0]['cCipherKey'] ?? 'ceremony2024secretkeyforqrencryption12';
        $iv = $settings[0]['cCipherIV'] ?? 'ceremony2024iv16';
        
        $decoded = base64_decode($encryptedData);
        return openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
    }
    return null;
}

// Generate barcode
function generateBarcode($cardNumber) {
    // Remove hyphens and convert to numeric barcode
    return str_replace('-', '', $cardNumber);
}

// =====================================================
// NOTIFICATIONS
// =====================================================

// Send points notification
function sendPointsNotification($userId, $storeId, $points, $action = 'earned') {
    $store = selectDB("stores", "`id` = '{$storeId}'");
    if (!$store) return;
    
    $storeName = direction($store[0]['enStoreName'], $store[0]['arStoreName']);
    
    if ($action == 'earned') {
        $enTitle = "Points Earned!";
        $arTitle = "تم كسب نقاط!";
        $enMessage = "You earned {$points} points at {$storeName}";
        $arMessage = "لقد كسبت {$points} نقطة في {$storeName}";
    } else {
        $enTitle = "Points Redeemed";
        $arTitle = "تم استبدال النقاط";
        $enMessage = "You redeemed {$points} points at {$storeName}";
        $arMessage = "لقد استبدلت {$points} نقطة في {$storeName}";
    }
    
    createNotification([
        'userId' => $userId,
        'storeId' => $storeId,
        'notificationType' => 1, // points_earned
        'enTitle' => $enTitle,
        'arTitle' => $arTitle,
        'enMessage' => $enMessage,
        'arMessage' => $arMessage
    ]);
}

// Send stamp notification
function sendStampNotification($userId, $storeId, $action, $stamps = 1) {
    $store = selectDB("stores", "`id` = '{$storeId}'");
    if (!$store) return;
    
    $storeName = direction($store[0]['enStoreName'], $store[0]['arStoreName']);
    
    if ($action == 'completed') {
        $enTitle = "Card Completed!";
        $arTitle = "اكتملت البطاقة!";
        $enMessage = "Congratulations! Your loyalty card at {$storeName} is complete. Redeem your reward now!";
        $arMessage = "تهانينا! بطاقة الولاء الخاصة بك في {$storeName} مكتملة. استبدل مكافأتك الآن!";
    } else {
        $enTitle = "Stamp Added!";
        $arTitle = "تمت إضافة ختم!";
        $enMessage = "You received {$stamps} stamp(s) at {$storeName}";
        $arMessage = "لقد حصلت على {$stamps} ختم في {$storeName}";
    }
    
    createNotification([
        'userId' => $userId,
        'storeId' => $storeId,
        'notificationType' => 1,
        'enTitle' => $enTitle,
        'arTitle' => $arTitle,
        'enMessage' => $enMessage,
        'arMessage' => $arMessage
    ]);
}

// Send redemption notification
function sendRedemptionNotification($userId, $storeId, $reward) {
    createNotification([
        'userId' => $userId,
        'storeId' => $storeId,
        'notificationType' => 2, // reward_available
        'enTitle' => 'Reward Ready!',
        'arTitle' => 'المكافأة جاهزة!',
        'enMessage' => 'Your reward "' . $reward['enTitle'] . '" is ready to use!',
        'arMessage' => 'مكافأتك "' . $reward['arTitle'] . '" جاهزة للاستخدام!'
    ]);
}

// Create notification
function createNotification($data) {
    GLOBAL $dbconnect;
    
    $sql = "INSERT INTO `customer_notifications` 
            (`userId`, `storeId`, `notificationType`, `enTitle`, `arTitle`, `enMessage`, `arMessage`, 
             `priority`, `deliveryMethod`, `sentStatus`, `hidden`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 2, '{\"push\":1,\"email\":0,\"sms\":0}', 0, 1, 0)";
    
    if ($stmt = $dbconnect->prepare($sql)) {
        $stmt->bind_param("iiissss",
            $data['userId'],
            $data['storeId'],
            $data['notificationType'],
            $data['enTitle'],
            $data['arTitle'],
            $data['enMessage'],
            $data['arMessage']
        );
        $stmt->execute();
        $stmt->close();
    }
}

// =====================================================
// ACHIEVEMENTS
// =====================================================

// Check and award achievements
function checkAchievements($userId, $storeId, $type, $value) {
    $achievements = selectDB("achievements", "`achievementType` = '{$type}' AND `status` = '0' AND `hidden` = '1'");
    
    if ($achievements) {
        foreach ($achievements as $achievement) {
            if ($value >= $achievement['triggerValue']) {
                // Check if already earned
                $existing = selectDB("user_achievements", 
                    "`userId` = '{$userId}' AND `achievementId` = '{$achievement['id']}' AND `status` = '0'");
                
                if (!$existing) {
                    // Award achievement
                    $earnedDate = date('Y-m-d H:i:s');
                    insertDB("user_achievements", [
                        'userId' => $userId,
                        'achievementId' => $achievement['id'],
                        'storeId' => $storeId,
                        'earnedDate' => $earnedDate,
                        'viewed' => 0,
                        'hidden' => 1,
                        'status' => 0
                    ]);
                    
                    // Award bonus points if configured
                    if ($achievement['bonusPoints'] > 0) {
                        $cards = selectDB("customer_cards", 
                            "`userId` = '{$userId}' AND `storeId` = '{$storeId}' AND `status` = '0' AND `cardStatus` = '1' LIMIT 1");
                        if ($cards) {
                            addPoints([
                                'cardId' => $cards[0]['id'],
                                'userId' => $userId,
                                'storeId' => $storeId,
                                'points' => $achievement['bonusPoints'],
                                'transactionType' => 6, // bonus
                                'description' => 'Achievement bonus: ' . direction($achievement['enTitle'], $achievement['arTitle'])
                            ]);
                        }
                    }
                }
            }
        }
    }
}

?>
