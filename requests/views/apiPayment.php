<?php
include_once("../../dashboard/includes/functions.php");

// Set headers for API response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    
    // Check if the data is valid JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON data'
        ]);
        exit;
    }
    
    // Check which action is requested
    $action = isset($data['action']) ? $data['action'] : '';
    
    switch ($action) {
        case 'createInvoice':
            // Validate required fields
            if (!isset($data['customer']) || !isset($data['order']) || !isset($data['reference'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing required fields'
                ]);
                exit;
            }
            
            // Create invoice
            $response = upaymentCreateInvoice($data);
            $responseData = json_decode($response, true);
            
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                echo json_encode([
                    'status' => 'success',
                    'data' => $responseData['data']
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create invoice',
                    'details' => $responseData
                ]);
            }
            break;
            
        case 'checkInvoice':
            // Validate required fields
            if (!isset($data['trackId'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing trackId'
                ]);
                exit;
            }
            
            // Check invoice status
            $response = upaymentCheckInvoice($data['trackId']);
            $responseData = json_decode($response, true);
            
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                echo json_encode([
                    'status' => 'success',
                    'data' => $responseData['data']
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to check invoice status',
                    'details' => $responseData
                ]);
            }
            break;
            
        default:
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action'
            ]);
            break;
    }
} else {
    // If the request method is not POST
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
?>
