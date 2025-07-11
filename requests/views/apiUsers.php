<?php

/**
 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="User API (register, login, forgetPassword, changePassword, sendCode, verifyCode)",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint"},
 *       @OA\Property(property="endpoint", type="string", example="register", description="Action: register, login, forgetPassword, changePassword, sendCode, verifyCode"),
 *       @OA\Property(property="fullName", type="string", example="John Doe"),
 *       @OA\Property(property="email", type="string", example="john@example.com"),
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="password", type="string", example="secret123"),
 *       @OA\Property(property="oldPassword", type="string", example="oldpass"),
 *       @OA\Property(property="newPassword", type="string", example="newpass"),
 *       @OA\Property(property="code", type="string", example="123456")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User API response",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string"),
 *       @OA\Property(property="token", type="string")
 *     )
 *   ),
 *   security={{"bearerAuth":{}}}
 * )
 */

// Unified Users endpoint logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_GET['a']) && $_GET['a'] === 'Users')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $endpoint = isset($input['endpoint']) ? $input['endpoint'] : '';
    switch ($endpoint) {
        case 'register':
            // ...registration logic...
            break;
        case 'login':
            // ...login logic...
            break;
        case 'forgetPassword':
            // ...forget password logic...
            break;
        case 'changePassword':
            // ...change password logic...
            break;
        case 'sendCode':
            // ...send WhatsApp code logic...
            break;
        case 'verifyCode':
            // ...verify WhatsApp code logic...
            break;
        default:
            echo json_encode(["status" => "error", "msg" => "Invalid endpoint."]);
            break;
    }
    exit;
}

// Helper: Check bearer token
function checkBearerToken($token) {
    // Query users table for keepMeAlive = $token
    // Return user row or false
    // ...implement...
}

// Helper: Send WhatsApp code (use your notification function)
function sendWhatsAppCode($phone, $code) {
    // Call your notification function here
    // ...implement...
}

// Helper: Verify WhatsApp code (implement code storage/validation)
function verifyWhatsAppCode($phone, $code) {
    // Check if code matches for phone
    // ...implement...
}

// ...existing code...
