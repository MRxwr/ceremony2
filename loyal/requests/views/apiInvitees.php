<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Add Invitee",
 *   description="Create a new invitee for an event",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "eventId", "name", "attendees", "countryCode", "mobile"},
 *       @OA\Property(property="endpoint", type="string", example="add"),
 *       @OA\Property(property="eventId", type="integer", example=123),
 *       @OA\Property(property="name", type="string", example="John Doe"),
 *       @OA\Property(property="attendees", type="integer", example=2),
 *       @OA\Property(property="countryCode", type="string", example="+1"),
 *       @OA\Property(property="mobile", type="string", example="1234567890")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Invitee created response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="msg", type="string", example="Invitee added successfully."),
 *         @OA\Property(property="inviteeId", type="integer", example=456),
 *         @OA\Property(property="inviteeCode", type="string", example="DEF456")
 *       )
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Edit Invitee",
 *   description="Edit an existing invitee by ID",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "inviteeId"},
 *       @OA\Property(property="endpoint", type="string", example="edit"),
 *       @OA\Property(property="inviteeId", type="integer", example=456),
 *       @OA\Property(property="eventId", type="integer", example=123),
 *       @OA\Property(property="name", type="string", example="Jane Doe"),
 *       @OA\Property(property="attendees", type="integer", example=3),
 *       @OA\Property(property="countryCode", type="string", example="+1"),
 *       @OA\Property(property="mobile", type="string", example="9876543210")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Invitee updated response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="msg", type="string", example="Invitee updated successfully."),
 *         @OA\Property(property="inviteeId", type="integer", example=456)
 *       )
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Delete Invitee",
 *   description="Soft delete an invitee by setting status to 1",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "inviteeId"},
 *       @OA\Property(property="endpoint", type="string", example="delete"),
 *       @OA\Property(property="inviteeId", type="integer", example=456)
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Invitee deleted response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Invitee deleted successfully."))
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Send Invitation",
 *   description="Send WhatsApp invitation to an invitee",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "inviteeId"},
 *       @OA\Property(property="endpoint", type="string", example="sendInvitation"),
 *       @OA\Property(property="inviteeId", type="integer", example=456)
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Invitation sent response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Invitation sent successfully."))
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Change Invitee Status",
 *   description="Change the confirmation status of an invitee (confirmed/declined)",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "inviteeId", "status"},
 *       @OA\Property(property="endpoint", type="string", example="changeStatus"),
 *       @OA\Property(property="inviteeId", type="integer", example=456),
 *       @OA\Property(property="status", type="string", enum={"confirmed", "declined"}, example="confirmed")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Status change response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Invitee status updated successfully."))
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Invitees",
 *   summary="Update RSVP Status",
 *   description="Allow an invitee to confirm or decline an invitation via a unique code (no authentication required)",
 *   tags={"Invitees"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "code", "status", "message"},
 *       @OA\Property(property="endpoint", type="string", example="rsvp"),
 *       @OA\Property(property="code", type="string", example="DEF456"),
 *       @OA\Property(property="status", type="string", enum={"confirmed", "declined"}, example="confirmed"),
 *       @OA\Property(property="message", type="string", example="I'm looking forward to attending!")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="RSVP response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Thank you for your response."))
 *     )
 *   )
 * )
 
 * @OA\Get(
 *   path="/requests/index.php?a=Invitees&endpoint=list",
 *   summary="List Invitees",
 *   description="Get a list of invitees for an event",
 *   tags={"Invitees"},
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(
 *     name="endpoint",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     example="list"
 *   ),
 *   @OA\Parameter(
 *     name="eventId",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="integer"),
 *     example="123",
 *     description="Event ID to list invitees for"
 *   ),
 *   @OA\Parameter(
 *     name="status",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string"),
 *     example="confirmed",
 *     description="Filter by status (confirmed, declined, pending)"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="List of invitees",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="invitees", type="array", @OA\Items(
 *           type="object",
 *           @OA\Property(property="id", type="integer", example=456),
 *           @OA\Property(property="name", type="string", example="John Doe"),
 *           @OA\Property(property="attendees", type="integer", example=2),
 *           @OA\Property(property="countryCode", type="string", example="+1"),
 *           @OA\Property(property="mobile", type="string", example="1234567890"),
 *           @OA\Property(property="invitationSent", type="integer", example=1),
 *           @OA\Property(property="isConfirmed", type="integer", example=1),
 *           @OA\Property(property="message", type="string", example="Looking forward to it!"),
 *           @OA\Property(property="code", type="string", example="DEF456")
 *         )),
 *         @OA\Property(property="stats", type="object", 
 *           @OA\Property(property="total", type="integer", example=10),
 *           @OA\Property(property="confirmed", type="integer", example=5),
 *           @OA\Property(property="declined", type="integer", example=2),
 *           @OA\Property(property="pending", type="integer", example=3),
 *           @OA\Property(property="totalAttendees", type="integer", example=15)
 *         )
 *       )
 *     )
 *   )
 * )
 */

// Unified Invitees endpoint logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_GET['a']) && $_GET['a'] === 'Invitees')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $endpoint = isset($input['endpoint']) ? $input['endpoint'] : '';
    
    // RSVP does not require authentication, all other POST endpoints do
    if ($endpoint !== 'rsvp') {
        // Authentication check
        $headers = getallheaders();
        $bearer = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';
        $user = checkBearerToken($bearer);
        
        if (!$user) {
            echo outputError(["msg" => "Authentication required."]);
            exit;
        }
    }
    
    switch ($endpoint) {
        case 'add':
            // Validate required fields
            $required = ['eventId', 'name', 'attendees', 'countryCode', 'mobile'];
            foreach ($required as $r) {
                if (empty($input[$r])) {
                    echo outputError(["msg" => "$r required."]);
                    exit;
                }
            }
            
            // Validate event exists
            $event = selectDBNew('events', [$input['eventId']], '`id` = ?', '');
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Check if user has access to this event
            if ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin') {
                echo outputError(["msg" => "Not authorized to add invitees to this event."]);
                exit;
            }
            
            // Generate unique code for invitee
            $code = generateRandomString();
            while (selectDBNew('invitees', [$code], '`code` = ?', '')) {
                $code = generateRandomString();
            }
            
            // Prepare data for insertion
            $data = [
                'eventId' => $input['eventId'],
                'name' => $input['name'],
                'attendees' => $input['attendees'],
                'countryCode' => $input['countryCode'],
                'mobile' => $input['mobile'],
                'code' => $code,
                'isConfirmed' => 0,      // Default: pending
                'invitationSent' => 0,   // Default: not sent
                'message' => '',
                'status' => 0,           // Default: active
                'hidden' => 0
            ];
            
            // Insert invitee
            if (insertDB('invitees', $data)) {
                $inviteeId = getLastInsertId();
                echo outputData([
                    "msg" => "Invitee added successfully.",
                    "inviteeId" => $inviteeId,
                    "inviteeCode" => $code
                ]);
            } else {
                echo outputError(["msg" => "Failed to add invitee."]);
            }
            break;
            
        case 'edit':
            // Validate inviteeId
            if (empty($input['inviteeId'])) {
                echo outputError(["msg" => "Invitee ID required."]);
                exit;
            }
            
            // Check if invitee exists
            $invitee = selectDBNew('invitees', [$input['inviteeId']], '`id` = ?', '');
            if (!$invitee) {
                echo outputError(["msg" => "Invitee not found."]);
                exit;
            }
            
            // Check event ownership
            $event = selectDBNew('events', [$invitee[0]['eventId']], '`id` = ?', '');
            if (!$event || ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin')) {
                echo outputError(["msg" => "Not authorized to edit this invitee."]);
                exit;
            }
            
            // Prepare update data
            $updateData = [];
            $fields = ['name', 'attendees', 'countryCode', 'mobile'];
            
            foreach ($fields as $field) {
                if (isset($input[$field])) {
                    $updateData[$field] = $input[$field];
                }
            }
            
            // If eventId is provided, check if user has access to the new event too
            if (isset($input['eventId']) && $input['eventId'] != $invitee[0]['eventId']) {
                $newEvent = selectDBNew('events', [$input['eventId']], '`id` = ?', '');
                if (!$newEvent || ($newEvent[0]['userId'] != $user['id'] && $user['role'] !== 'admin')) {
                    echo outputError(["msg" => "Not authorized to move invitee to this event."]);
                    exit;
                }
                $updateData['eventId'] = $input['eventId'];
            }
            
            // Update invitee
            if (!empty($updateData)) {
                if (updateDB('invitees', $updateData, "`id` = '{$input['inviteeId']}'")) {
                    echo outputData([
                        "msg" => "Invitee updated successfully.",
                        "inviteeId" => $input['inviteeId']
                    ]);
                } else {
                    echo outputError(["msg" => "Failed to update invitee."]);
                }
            } else {
                echo outputError(["msg" => "No data to update."]);
            }
            break;
            
        case 'delete':
            // Validate inviteeId
            if (empty($input['inviteeId'])) {
                echo outputError(["msg" => "Invitee ID required."]);
                exit;
            }
            
            // Check if invitee exists
            $invitee = selectDBNew('invitees', [$input['inviteeId']], '`id` = ?', '');
            if (!$invitee) {
                echo outputError(["msg" => "Invitee not found."]);
                exit;
            }
            
            // Check event ownership
            $event = selectDBNew('events', [$invitee[0]['eventId']], '`id` = ?', '');
            if (!$event || ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin')) {
                echo outputError(["msg" => "Not authorized to delete this invitee."]);
                exit;
            }
            
            // Soft delete invitee by setting status to 1
            if (updateDB('invitees', ['status' => '1'], "`id` = '{$input['inviteeId']}'")) {
                echo outputData(["msg" => "Invitee deleted successfully."]);
            } else {
                echo outputError(["msg" => "Failed to delete invitee."]);
            }
            break;
            
        case 'sendInvitation':
            // Validate inviteeId
            if (empty($input['inviteeId'])) {
                echo outputError(["msg" => "Invitee ID required."]);
                exit;
            }
            
            // Get invitee data
            $invitee = selectDBNew('invitees', [$input['inviteeId']], '`id` = ?', '');
            if (!$invitee) {
                echo outputError(["msg" => "Invitee not found."]);
                exit;
            }
            
            // Check event ownership
            $event = selectDBNew('events', [$invitee[0]['eventId']], '`id` = ?', '');
            if (!$event || ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin')) {
                echo outputError(["msg" => "Not authorized to send invitations for this event."]);
                exit;
            }
            
            // Construct WhatsApp message
            $to = $invitee[0]['countryCode'] . $invitee[0]['mobile'];
            $eventCode = $event[0]['code'];
            $inviteeCode = $invitee[0]['code'];
            
            // Construct invitee link
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'yourwebsite.com';
            $inviteeLink = "https://" . $host . "/{$eventCode}?i={$inviteeCode}";
            
            // Send WhatsApp message
            $result = whatsappUltraMsgImage($to, $invitee[0]['eventId'], $inviteeLink);
            
            if ($result) {
                // Update invitation sent status
                updateDB('invitees', ['invitationSent' => '1'], "`id` = '{$input['inviteeId']}'");
                echo outputData(["msg" => "Invitation sent successfully."]);
            } else {
                echo outputError(["msg" => "Failed to send invitation via WhatsApp."]);
            }
            break;
            
        case 'changeStatus':
            // Validate inviteeId and status
            if (empty($input['inviteeId'])) {
                echo outputError(["msg" => "Invitee ID required."]);
                exit;
            }
            
            if (empty($input['status']) || !in_array($input['status'], ['confirmed', 'declined'])) {
                echo outputError(["msg" => "Valid status required (confirmed or declined)."]);
                exit;
            }
            
            // Check if invitee exists
            $invitee = selectDBNew('invitees', [$input['inviteeId']], '`id` = ?', '');
            if (!$invitee) {
                echo outputError(["msg" => "Invitee not found."]);
                exit;
            }
            
            // Check event ownership
            $event = selectDBNew('events', [$invitee[0]['eventId']], '`id` = ?', '');
            if (!$event || ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin')) {
                echo outputError(["msg" => "Not authorized to change status for this invitee."]);
                exit;
            }
            
            // Map status to database values
            $statusValue = $input['status'] === 'confirmed' ? 1 : 2;
            
            // Update status
            if (updateDB('invitees', ['isConfirmed' => $statusValue], "`id` = '{$input['inviteeId']}'")) {
                echo outputData(["msg" => "Invitee status updated successfully."]);
            } else {
                echo outputError(["msg" => "Failed to update invitee status."]);
            }
            break;
            
        case 'rsvp':
            // This endpoint is publicly accessible via invitee code
            // Validate required fields
            if (empty($input['code'])) {
                echo outputError(["msg" => "Invitee code required."]);
                exit;
            }
            
            if (empty($input['status']) || !in_array($input['status'], ['confirmed', 'declined'])) {
                echo outputError(["msg" => "Valid status required (confirmed or declined)."]);
                exit;
            }
            
            // Check if invitee exists
            $invitee = selectDBNew('invitees', [$input['code']], '`code` = ?', '');
            if (!$invitee) {
                echo outputError(["msg" => "Invalid invitation code."]);
                exit;
            }
            
            // Map status to database values
            $statusValue = $input['status'] === 'confirmed' ? 1 : 2;
            
            // Prepare update data
            $updateData = [
                'isConfirmed' => $statusValue
            ];
            
            // Add message if provided
            if (isset($input['message'])) {
                $updateData['message'] = $input['message'];
            }
            
            // Update invitee response
            if (updateDB('invitees', $updateData, "`code` = '{$input['code']}'")) {
                $responseMsg = $input['status'] === 'confirmed' ? 
                    "Thank you for confirming your attendance." : 
                    "Thank you for your response. We're sorry you can't make it.";
                echo outputData(["msg" => $responseMsg]);
            } else {
                echo outputError(["msg" => "Failed to update your response."]);
            }
            break;
            
        default:
            echo outputError(["msg" => "Invalid endpoint."]);
            break;
    }
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['a']) && $_GET['a'] === 'Invitees')) {
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
    
    // Authentication check for all GET endpoints
    $headers = getallheaders();
    $bearer = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';
    $user = checkBearerToken($bearer);
    
    if (!$user) {
        echo outputError(["msg" => "Authentication required."]);
        exit;
    }
    
    switch ($endpoint) {
        case 'list':
            // Validate eventId
            if (empty($_GET['eventId'])) {
                echo outputError(["msg" => "Event ID required."]);
                exit;
            }
            
            // Check if event exists and user has access
            $event = selectDBNew('events', [$_GET['eventId']], '`id` = ?', '');
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Check event ownership
            if ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin') {
                echo outputError(["msg" => "Not authorized to view invitees for this event."]);
                exit;
            }
            
            // Build query for invitees
            $where = "`eventId` = ? AND `status` = '0' AND `hidden` = '0'";
            $params = [$_GET['eventId']];
            
            // Add status filter if provided
            if (isset($_GET['status'])) {
                if ($_GET['status'] === 'confirmed') {
                    $where .= " AND `isConfirmed` = '1'";
                } elseif ($_GET['status'] === 'declined') {
                    $where .= " AND `isConfirmed` = '2'";
                } elseif ($_GET['status'] === 'pending') {
                    $where .= " AND `isConfirmed` = '0'";
                }
            }
            
            // Get invitees
            $invitees = selectDBNew('invitees', $params, $where, ' ORDER BY `id` ASC');
            
            // Format invitees for response
            $formattedInvitees = [];
            if ($invitees) {
                foreach ($invitees as $invitee) {
                    $formattedInvitees[] = [
                        'id' => $invitee['id'],
                        'name' => $invitee['name'],
                        'attendees' => $invitee['attendees'],
                        'countryCode' => $invitee['countryCode'],
                        'mobile' => $invitee['mobile'],
                        'invitationSent' => $invitee['invitationSent'],
                        'isConfirmed' => $invitee['isConfirmed'],
                        'message' => $invitee['message'],
                        'code' => $invitee['code']
                    ];
                }
            }
            
            // Get statistics
            $totalQuery = selectDBNew('invitees', [$_GET['eventId']], "`eventId` = ? AND `status` = '0' AND `hidden` = '0'", '');
            $confirmedQuery = selectDBNew('invitees', [$_GET['eventId']], "`eventId` = ? AND `status` = '0' AND `hidden` = '0' AND `isConfirmed` = '1'", '');
            $declinedQuery = selectDBNew('invitees', [$_GET['eventId']], "`eventId` = ? AND `status` = '0' AND `hidden` = '0' AND `isConfirmed` = '2'", '');
            
            $total = $totalQuery ? count($totalQuery) : 0;
            $confirmed = $confirmedQuery ? count($confirmedQuery) : 0;
            $declined = $declinedQuery ? count($declinedQuery) : 0;
            $pending = $total - $confirmed - $declined;
            
            // Calculate total attendees (sum of all confirmed attendees)
            $totalAttendees = 0;
            if ($confirmedQuery) {
                foreach ($confirmedQuery as $inv) {
                    $totalAttendees += $inv['attendees'];
                }
            }
            
            $stats = [
                'total' => $total,
                'confirmed' => $confirmed,
                'declined' => $declined,
                'pending' => $pending,
                'totalAttendees' => $totalAttendees
            ];
            
            echo outputData([
                "invitees" => $formattedInvitees,
                "stats" => $stats
            ]);
            break;
            
        default:
            echo outputError(["msg" => "Invalid endpoint."]);
            break;
    }
    exit;
} else {
    echo outputError(["msg" => "Invalid request method or endpoint."]);
    exit;
}

// Helper function to get the last inserted ID
function getLastInsertId() {
    global $con;
    return mysqli_insert_id($con);
}

// Helper function: Generate a random string for invitee codes (copied for consistency)
function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Check bearer token (copied from apiUsers.php for consistency)
function checkBearerToken($token) {
    if (empty($token)) return false;
    $user = selectDBNew('users', [$token], '`keepMeAlive` = ?', '');
    return ($user && isset($user[0])) ? $user[0] : false;
}
