<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * @OA\Post(
 *   path="/requests/index.php?a=Events",
 *   summary="Add Event",
 *   description="Create a new event with details",
 *   tags={"Events"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "title", "eventDate", "eventTime", "location", "venueName", "venueAddress", "details"},
 *       @OA\Property(property="endpoint", type="string", example="add"),
 *       @OA\Property(property="title", type="string", example="Wedding Ceremony"),
 *       @OA\Property(property="language", type="string", example="ltr"),
 *       @OA\Property(property="categoryId", type="integer", example=1),
 *       @OA\Property(property="eventDate", type="string", example="2025-09-15"),
 *       @OA\Property(property="eventTime", type="string", example="18:00"),
 *       @OA\Property(property="location", type="string", example="Dubai"),
 *       @OA\Property(property="venueName", type="string", example="Grand Hotel"),
 *       @OA\Property(property="venueAddress", type="string", example="123 Main St"),
 *       @OA\Property(property="details", type="string", example="<p>Join us for our special day</p>"),
 *       @OA\Property(property="terms", type="string", example="<p>Terms and conditions apply</p>"),
 *       @OA\Property(property="sound", type="string", example="wedding-march.mp3"),
 *       @OA\Property(property="video", type="string", example="https://youtube.com/watch?v=123"),
 *       @OA\Property(property="whatsappCaption", type="string", example="You're invited to our wedding!"),
 *       @OA\Property(property="background", type="string", format="binary", description="Background image in base64 format"),
 *       @OA\Property(property="whatsappImage", type="string", format="binary", description="WhatsApp image in base64 format"),
 *       @OA\Property(property="gallery", type="array", @OA\Items(type="string", format="binary", description="Gallery images in base64 format"))
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Event created response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="msg", type="string", example="Event created successfully."),
 *         @OA\Property(property="eventId", type="integer", example=123),
 *         @OA\Property(property="eventCode", type="string", example="ABC123")
 *       )
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Events",
 *   summary="Edit Event",
 *   description="Edit an existing event by ID",
 *   tags={"Events"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "eventId"},
 *       @OA\Property(property="endpoint", type="string", example="edit"),
 *       @OA\Property(property="eventId", type="integer", example=123),
 *       @OA\Property(property="title", type="string", example="Updated Wedding Ceremony"),
 *       @OA\Property(property="language", type="string", example="ltr"),
 *       @OA\Property(property="categoryId", type="integer", example=1),
 *       @OA\Property(property="eventDate", type="string", example="2025-09-20"),
 *       @OA\Property(property="eventTime", type="string", example="19:00"),
 *       @OA\Property(property="location", type="string", example="Dubai"),
 *       @OA\Property(property="venueName", type="string", example="Luxury Hotel"),
 *       @OA\Property(property="venueAddress", type="string", example="456 Main St"),
 *       @OA\Property(property="details", type="string", example="<p>Join us for our special day - updated</p>"),
 *       @OA\Property(property="terms", type="string", example="<p>Terms and conditions apply</p>"),
 *       @OA\Property(property="sound", type="string", example="new-wedding-march.mp3"),
 *       @OA\Property(property="video", type="string", example="https://youtube.com/watch?v=456"),
 *       @OA\Property(property="whatsappCaption", type="string", example="Updated invitation!"),
 *       @OA\Property(property="background", type="string", format="binary", description="Background image in base64 format"),
 *       @OA\Property(property="whatsappImage", type="string", format="binary", description="WhatsApp image in base64 format"),
 *       @OA\Property(property="gallery", type="array", @OA\Items(type="string", format="binary", description="New gallery images to add in base64 format")),
 *       @OA\Property(property="removeGalleryImages", type="array", @OA\Items(type="string", example="image1.jpg"), description="List of gallery image filenames to remove")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Event updated response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="msg", type="string", example="Event updated successfully."),
 *         @OA\Property(property="eventId", type="integer", example=123)
 *       )
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Events",
 *   summary="Hide Event",
 *   description="Hide an event by setting status to 1",
 *   tags={"Events"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "eventId"},
 *       @OA\Property(property="endpoint", type="string", example="hide"),
 *       @OA\Property(property="eventId", type="integer", example=123)
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Event hidden response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Event hidden successfully."))
 *     )
 *   )
 * )
 
 * @OA\Post(
 *   path="/requests/index.php?a=Events",
 *   summary="Delete Event",
 *   description="Permanently delete an event",
 *   tags={"Events"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "eventId"},
 *       @OA\Property(property="endpoint", type="string", example="delete"),
 *       @OA\Property(property="eventId", type="integer", example=123)
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Event deleted response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Event deleted successfully."))
 *     )
 *   )
 * )
 
 * @OA\Get(
 *   path="/requests/index.php?a=Events&endpoint=list",
 *   summary="List Events",
 *   description="Get a list of events",
 *   tags={"Events"},
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(
 *     name="endpoint",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     example="list"
 *   ),
 *   @OA\Parameter(
 *     name="categoryId",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer"),
 *     example="1",
 *     description="Filter by category ID"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="List of events",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="events", type="array", @OA\Items(
 *           type="object",
 *           @OA\Property(property="id", type="integer", example=123),
 *           @OA\Property(property="title", type="string", example="Wedding Ceremony"),
 *           @OA\Property(property="code", type="string", example="ABC123"),
 *           @OA\Property(property="eventDate", type="string", example="2025-09-15"),
 *           @OA\Property(property="eventTime", type="string", example="18:00"),
 *           @OA\Property(property="location", type="string", example="Dubai"),
 *           @OA\Property(property="background", type="string", example="image.jpg"),
 *           @OA\Property(property="categoryId", type="integer", example=1),
 *           @OA\Property(property="categoryName", type="string", example="Wedding")
 *         ))
 *       )
 *     )
 *   )
 * )

 * @OA\Get(
 *   path="/requests/index.php?a=Events&endpoint=details",
 *   summary="Get Event Details",
 *   description="Get detailed information about a specific event",
 *   tags={"Events"},
 *   @OA\Parameter(
 *     name="endpoint",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     example="details"
 *   ),
 *   @OA\Parameter(
 *     name="eventId",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer"),
 *     example="123",
 *     description="Event ID"
 *   ),
 *   @OA\Parameter(
 *     name="code",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string"),
 *     example="ABC123",
 *     description="Event Code"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Event details",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="event", type="object",
 *           @OA\Property(property="id", type="integer", example=123),
 *           @OA\Property(property="title", type="string", example="Wedding Ceremony"),
 *           @OA\Property(property="code", type="string", example="ABC123"),
 *           @OA\Property(property="eventDate", type="string", example="2025-09-15"),
 *           @OA\Property(property="eventTime", type="string", example="18:00"),
 *           @OA\Property(property="location", type="string", example="Dubai"),
 *           @OA\Property(property="venueName", type="string", example="Grand Hotel"),
 *           @OA\Property(property="venueAddress", type="string", example="123 Main St"),
 *           @OA\Property(property="details", type="string", example="<p>Join us for our special day</p>"),
 *           @OA\Property(property="terms", type="string", example="<p>Terms and conditions apply</p>"),
 *           @OA\Property(property="sound", type="string", example="wedding-march.mp3"),
 *           @OA\Property(property="video", type="string", example="https://youtube.com/watch?v=123"),
 *           @OA\Property(property="background", type="string", example="image.jpg"),
 *           @OA\Property(property="whatsappImage", type="string", example="whatsapp.jpg"),
 *           @OA\Property(property="whatsappCaption", type="string", example="You're invited!"),
 *           @OA\Property(property="gallery", type="array", @OA\Items(type="string", example="gallery1.jpg")),
 *           @OA\Property(property="categoryId", type="integer", example=1),
 *           @OA\Property(property="categoryName", type="string", example="Wedding"),
 *           @OA\Property(property="language", type="string", example="ltr")
 *         )
 *       )
 *     )
 *   )
 * )
 */

// Unified Events endpoint logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_GET['a']) && $_GET['a'] === 'Events')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $endpoint = isset($input['endpoint']) ? $input['endpoint'] : '';
    
    // Authentication check for all endpoints except 'details'
    $headers = getallheaders();
    $bearer = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';
    $user = checkBearerToken($bearer);
    
    if (!$user) {
        echo outputError(["msg" => "Authentication required."]);
        exit;
    }
    
    switch ($endpoint) {
        case 'add':
            // Validate required fields
            $required = ['title', 'eventDate', 'eventTime', 'location', 'venueName', 'venueAddress', 'details'];
            foreach ($required as $r) {
                if (empty($input[$r])) {
                    echo outputError(["msg" => "$r required."]);
                    exit;
                }
            }
            
            // Generate unique code
            $code = generateRandomString();
            while (selectDBNew('events', [$code], '`code` = ?', '')) {
                $code = generateRandomString();
            }
            
            // Prepare data for insertion
            $data = [
                'title' => $input['title'],
                'language' => isset($input['language']) ? $input['language'] : 'ltr',
                'categoryId' => isset($input['categoryId']) ? $input['categoryId'] : 1,
                'eventDate' => $input['eventDate'],
                'eventTime' => $input['eventTime'],
                'location' => $input['location'],
                'venueName' => $input['venueName'],
                'venueAddress' => $input['venueAddress'],
                'details' => $input['details'],
                'terms' => isset($input['terms']) ? $input['terms'] : '',
                'sound' => isset($input['sound']) ? $input['sound'] : '',
                'video' => isset($input['video']) ? $input['video'] : '',
                'code' => $code,
                'status' => 0,
                'userId' => $user['id'],
                'whatsappCaption' => isset($input['whatsappCaption']) ? $input['whatsappCaption'] : ''
            ];
            
            // Handle image uploads
            if (isset($input['background']) && !empty($input['background'])) {
                $data['background'] = saveBase64Image($input['background']);
            } else {
                $data['background'] = '';
            }
            
            if (isset($input['whatsappImage']) && !empty($input['whatsappImage'])) {
                $data['whatsappImage'] = saveBase64Image($input['whatsappImage']);
            } else {
                $data['whatsappImage'] = '';
            }
            
            // Handle gallery images
            $galleryImages = [];
            if (isset($input['gallery']) && is_array($input['gallery'])) {
                foreach ($input['gallery'] as $galleryImage) {
                    $savedImage = saveBase64Image($galleryImage);
                    if (!empty($savedImage)) {
                        $galleryImages[] = $savedImage;
                    }
                }
            }
            $data['gallery'] = json_encode($galleryImages);
            
            // Insert event into database
            if (insertDB('events', $data)) {
                $eventId = getLastInsertId();
                echo outputData([
                    "msg" => "Event created successfully.",
                    "eventId" => $eventId,
                    "eventCode" => $code
                ]);
            } else {
                echo outputError(["msg" => "Failed to create event."]);
            }
            break;
            
        case 'edit':
            // Validate eventId
            if (empty($input['eventId'])) {
                echo outputError(["msg" => "Event ID required."]);
                exit;
            }
            
            // Check if event exists and belongs to the user
            $event = selectDBNew('events', [$input['eventId']], '`id` = ?', '');
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Only allow editing of own events (or admin override)
            if ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin') {
                echo outputError(["msg" => "Not authorized to edit this event."]);
                exit;
            }
            
            // Prepare update data
            $updateData = [];
            
            // Update text fields if provided
            $textFields = [
                'title', 'language', 'categoryId', 'eventDate', 'eventTime', 
                'location', 'venueName', 'venueAddress', 'details', 'terms',
                'sound', 'video', 'whatsappCaption'
            ];
            
            foreach ($textFields as $field) {
                if (isset($input[$field])) {
                    $updateData[$field] = $input[$field];
                }
            }
            
            // Handle image uploads
            if (isset($input['background']) && !empty($input['background'])) {
                $updateData['background'] = saveBase64Image($input['background']);
            }
            
            if (isset($input['whatsappImage']) && !empty($input['whatsappImage'])) {
                $updateData['whatsappImage'] = saveBase64Image($input['whatsappImage']);
            }
            
            // Handle gallery updates
            if (isset($input['gallery']) || isset($input['removeGalleryImages'])) {
                // Get existing gallery
                $existingGallery = [];
                if (!empty($event[0]['gallery'])) {
                    $existingGallery = json_decode($event[0]['gallery'], true);
                    if (!is_array($existingGallery)) {
                        $existingGallery = [];
                    }
                }
                
                // Remove images if specified
                if (isset($input['removeGalleryImages']) && is_array($input['removeGalleryImages'])) {
                    $existingGallery = array_diff($existingGallery, $input['removeGalleryImages']);
                    // Re-index array
                    $existingGallery = array_values($existingGallery);
                }
                
                // Add new images
                if (isset($input['gallery']) && is_array($input['gallery'])) {
                    foreach ($input['gallery'] as $galleryImage) {
                        $savedImage = saveBase64Image($galleryImage);
                        if (!empty($savedImage)) {
                            $existingGallery[] = $savedImage;
                        }
                    }
                }
                
                $updateData['gallery'] = json_encode($existingGallery);
            }
            
            // Update event in database
            if (!empty($updateData)) {
                if (updateDB('events', $updateData, "`id` = '{$input['eventId']}'")) {
                    echo outputData([
                        "msg" => "Event updated successfully.",
                        "eventId" => $input['eventId']
                    ]);
                } else {
                    echo outputError(["msg" => "Failed to update event."]);
                }
            } else {
                echo outputError(["msg" => "No data to update."]);
            }
            break;
            
        case 'hide':
            // Validate eventId
            if (empty($input['eventId'])) {
                echo outputError(["msg" => "Event ID required."]);
                exit;
            }
            
            // Check if event exists and belongs to the user
            $event = selectDBNew('events', [$input['eventId']], '`id` = ?', '');
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Only allow hiding of own events (or admin override)
            if ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin') {
                echo outputError(["msg" => "Not authorized to hide this event."]);
                exit;
            }
            
            // Update status to hidden (1)
            if (updateDB('events', ['status' => '1'], "`id` = '{$input['eventId']}'")) {
                echo outputData(["msg" => "Event hidden successfully."]);
            } else {
                echo outputError(["msg" => "Failed to hide event."]);
            }
            break;
            
        case 'delete':
            // Validate eventId
            if (empty($input['eventId'])) {
                echo outputError(["msg" => "Event ID required."]);
                exit;
            }
            
            // Check if event exists and belongs to the user
            $event = selectDBNew('events', [$input['eventId']], '`id` = ?', '');
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Only allow deletion of own events (or admin override)
            if ($event[0]['userId'] != $user['id'] && $user['role'] !== 'admin') {
                echo outputError(["msg" => "Not authorized to delete this event."]);
                exit;
            }
            
            // Permanently delete event
            if (deleteDBNew('events', [$input['eventId']], '`id` = ?')) {
                echo outputData(["msg" => "Event deleted successfully."]);
            } else {
                echo outputError(["msg" => "Failed to delete event."]);
            }
            break;
            
        default:
            echo outputError(["msg" => "Invalid endpoint."]);
            break;
    }
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['a']) && $_GET['a'] === 'Events')) {
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
    
    switch ($endpoint) {
        case 'list':
            // Check authentication
            $headers = getallheaders();
            $bearer = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';
            $user = checkBearerToken($bearer);
            
            if (!$user) {
                echo outputError(["msg" => "Authentication required."]);
                exit;
            }
            
            // Build query based on filters
            $params = [];
            $where = "`status` = '0'"; // Only active events
            
            // Filter by user ID (show only user's events unless admin)
            if ($user['role'] !== 'admin') {
                $where .= " AND `userId` = ?";
                $params[] = $user['id'];
            }
            
            // Filter by category if provided
            if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                $where .= " AND `categoryId` = ?";
                $params[] = $_GET['categoryId'];
            }
            
            // Get events with category names
            $joinData = array(
                "select" => [
                    "t.id", "t.title", "t.code", "t.eventDate", "t.eventTime", 
                    "t.location", "t.background", "t.categoryId",
                    "t1.enTitle as categoryEnTitle", "t1.arTitle as categoryArTitle"
                ],
                "join" => ["categories"],
                "on" => ["t.categoryId = t1.id"]
            );
            
            $events = selectJoinDB("events", $joinData, $where, $params);
            
            // Format response
            $formattedEvents = [];
            if ($events) {
                foreach ($events as $event) {
                    $formattedEvents[] = [
                        'id' => $event['id'],
                        'title' => $event['title'],
                        'code' => $event['code'],
                        'eventDate' => $event['eventDate'],
                        'eventTime' => $event['eventTime'],
                        'location' => $event['location'],
                        'background' => $event['background'],
                        'categoryId' => $event['categoryId'],
                        'categoryName' => $event['categoryEnTitle'] // Default to English title
                    ];
                }
            }
            
            echo outputData(["events" => $formattedEvents]);
            break;
            
        case 'details':
            // Validate parameters
            if (empty($_GET['eventId']) && empty($_GET['code'])) {
                echo outputError(["msg" => "Event ID or code required."]);
                exit;
            }
            
            // Build query based on provided parameter
            $params = [];
            if (!empty($_GET['eventId'])) {
                $where = "`id` = ?";
                $params[] = $_GET['eventId'];
            } else {
                $where = "`code` = ?";
                $params[] = $_GET['code'];
            }
            
            // Add status check to only show active events
            $where .= " AND `status` = '0'";
            
            // Get event with category name
            $joinData = array(
                "select" => [
                    "t.*", "t1.enTitle as categoryEnTitle", "t1.arTitle as categoryArTitle"
                ],
                "join" => ["categories"],
                "on" => ["t.categoryId = t1.id"]
            );
            
            $event = selectJoinDB("events", $joinData, $where, $params);
            
            if (!$event) {
                echo outputError(["msg" => "Event not found."]);
                exit;
            }
            
            // Format response
            $eventData = $event[0];
            
            // Parse gallery JSON
            $gallery = [];
            if (!empty($eventData['gallery'])) {
                $gallery = json_decode($eventData['gallery'], true);
                if (!is_array($gallery)) {
                    $gallery = [];
                }
            }
            
            // Build formatted event object
            $formattedEvent = [
                'id' => $eventData['id'],
                'title' => $eventData['title'],
                'code' => $eventData['code'],
                'eventDate' => $eventData['eventDate'],
                'eventTime' => $eventData['eventTime'],
                'location' => $eventData['location'],
                'venueName' => $eventData['venueName'],
                'venueAddress' => $eventData['venueAddress'],
                'details' => $eventData['details'],
                'terms' => $eventData['terms'],
                'sound' => $eventData['sound'],
                'video' => $eventData['video'],
                'background' => $eventData['background'],
                'whatsappImage' => $eventData['whatsappImage'],
                'whatsappCaption' => $eventData['whatsappCaption'],
                'gallery' => $gallery,
                'categoryId' => $eventData['categoryId'],
                'categoryName' => $eventData['categoryEnTitle'], // Default to English title
                'language' => $eventData['language']
            ];
            
            echo outputData(["event" => $formattedEvent]);
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

/**
 * Helper function to save base64 encoded image
 * @param string $base64Image The base64 encoded image data
 * @return string The saved image filename or empty string on failure
 */
function saveBase64Image($base64Image) {
    // Check if the string is a valid base64 image
    if (strpos($base64Image, 'data:image/') !== 0) {
        return '';
    }
    
    // Extract the image data
    $parts = explode(',', $base64Image);
    if (count($parts) < 2) {
        return '';
    }
    
    // Get the image type from the header
    $matches = [];
    preg_match('/data:image\/([a-zA-Z]+);base64/', $parts[0], $matches);
    if (!isset($matches[1])) {
        return '';
    }
    $imageType = $matches[1];
    
    // Decode the image data
    $imageData = base64_decode($parts[1]);
    if (!$imageData) {
        return '';
    }
    
    // Generate a unique filename
    $filename = uniqid() . '.' . $imageType;
    $filepath = __DIR__ . '/../../logos/' . $filename;
    
    // Save the image
    if (file_put_contents($filepath, $imageData)) {
        return $filename;
    }
    
    return '';
}

/**
 * Helper function to generate a random string for event codes
 * @param int $length The length of the string
 * @return string The generated random string
 */
function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Helper function to get the last inserted ID
 * @return int The last insert ID
 */
function getLastInsertId() {
    global $con;
    return mysqli_insert_id($con);
}

// Check bearer token (copied from apiUsers.php for consistency)
function checkBearerToken($token) {
    if (empty($token)) return false;
    $user = selectDBNew('users', [$token], '`keepMeAlive` = ?', '');
    return ($user && isset($user[0])) ? $user[0] : false;
}
