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
 *       @OA\Property(property="fullName", type="string", example="John Doe", description="Required for register"),
 *       @OA\Property(property="email", type="string", example="john@example.com", description="Required for register"),
 *       @OA\Property(property="phone", type="string", example="1234567890", description="Required for all actions except changePassword"),
 *       @OA\Property(property="password", type="string", example="secret123", description="Required for register/login"),
 *       @OA\Property(property="oldPassword", type="string", example="oldpass", description="Required for changePassword"),
 *       @OA\Property(property="newPassword", type="string", example="newpass", description="Required for changePassword"),
 *       @OA\Property(property="code", type="string", example="123456", description="Required for register/verifyCode/forgetPassword")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User API response (see endpoint for details)",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="error", type="string", example="0"),
 *       @OA\Property(property="status", type="string", example="successful"),
 *       @OA\Property(property="data", type="object", oneOf={
 *         @OA\Schema(
 *           @OA\Property(property="msg", type="string", example="Login successful."),
 *           @OA\Property(property="token", type="string", example="abcdef123456...")
 *         ),
 *         @OA\Schema(
 *           @OA\Property(property="msg", type="string", example="Verification code sent.")
 *         ),
 *         @OA\Schema(
 *           @OA\Property(property="msg", type="string", example="Password changed.")
 *         )
 *       })
 *     )
 *   ),
 *   security={{"bearerAuth":{}}}
 * )
 */

// Unified Users endpoint logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_GET['a']) && $_GET['a'] === 'Users')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $endpoint = isset($input['endpoint']) ? $input['endpoint'] : '';
    session_start();
    switch ($endpoint) {
        case 'sendCode':
            if (empty($input['phone'])) {
                echo outputError(["msg" => "Phone required."]);
                break;
            }
            $code = rand(100000, 999999);
            $_SESSION['verify_code_' . $input['phone']] = $code;
            sendWhatsAppCode($input['phone'], $code);
            echo outputData(["msg" => "Verification code sent."]);
            break;
        case 'verifyCode':
            if (empty($input['phone']) || empty($input['code'])) {
                echo outputError(["msg" => "Phone and code required."]);
                break;
            }
            if (verifyWhatsAppCode($input['phone'], $input['code'])) {
                echo outputData(["msg" => "Code verified."]);
            } else {
                echo outputError(["msg" => "Invalid code."]);
            }
            break;
        case 'register':
            $required = ['fullName','email','phone','password','code'];
            foreach ($required as $r) {
                if (empty($input[$r])) {
                    echo outputError(["msg" => "$r required."]);
                    exit;
                }
            }
            if (!verifyWhatsAppCode($input['phone'], $input['code'])) {
                echo outputError(["msg" => "Phone not verified."]);
                break;
            }
            $exists = selectDBNew('users', [$input['phone']], '`phone` = ?', '');
            if ($exists && is_array($exists) && count($exists) > 0) {
                echo outputError(["msg" => "Phone already registered."]);
                break;
            }
            $data = [
                'fullName' => $input['fullName'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
                'keepMeAlive' => '',
            ];
            if (insertDB('users', $data)) {
                echo outputData(["msg" => "Registration successful."]);
            } else {
                echo outputError(["msg" => "Registration failed."]);
            }
            break;
        case 'login':
            if (empty($input['phone']) || empty($input['password'])) {
                echo outputError(["msg" => "Phone and password required."]);
                break;
            }
            $user = selectDBNew('users', [$input['phone']], '`phone` = ?', '');
            if ($user && password_verify($input['password'], $user[0]['password'])) {
                $token = bin2hex(random_bytes(32));
                updateDB('users', ['keepMeAlive' => $token], "`id` = '{$user[0]['id']}'");
                echo outputData(["msg" => "Login successful.", "token" => $token]);
            } else {
                echo outputError(["msg" => "Invalid credentials."]);
            }
            break;
        case 'forgetPassword':
            if (empty($input['phone'])) {
                echo outputError(["msg" => "Phone required."]);
                break;
            }
            $user = selectDBNew('users', [$input['phone']], '`phone` = ?', '');
            if (!$user) {
                echo outputError(["msg" => "User not found."]);
                break;
            }
            $code = rand(100000, 999999);
            $_SESSION['verify_code_' . $input['phone']] = $code;
            sendWhatsAppCode($input['phone'], $code);
            echo outputData(["msg" => "Password reset code sent."]);
            break;
        case 'changePassword':
            if (empty($input['oldPassword']) || empty($input['newPassword'])) {
                echo outputError(["msg" => "Old and new password required."]);
                break;
            }
            $headers = getallheaders();
            $bearer = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';
            $user = checkBearerToken($bearer);
            if (!$user) {
                echo outputError(["msg" => "Invalid or missing token."]);
                break;
            }
            if (!password_verify($input['oldPassword'], $user['password'])) {
                echo outputError(["msg" => "Old password incorrect."]);
                break;
            }
            updateDB('users', ['password' => password_hash($input['newPassword'], PASSWORD_DEFAULT)], "`id` = '{$user['id']}'");
            echo outputData(["msg" => "Password changed."]);
            break;
        default:
            echo outputError(["msg" => "Invalid endpoint."]);
            break;
    }
    exit;
}

// Helper: Check bearer token
function checkBearerToken($token) {
    if (empty($token)) return false;
    $user = selectDBNew('users', [$token], '`keepMeAlive` = ?', '');
    return ($user && isset($user[0])) ? $user[0] : false;
}

// Helper: Send WhatsApp code (use your notification function)
function sendWhatsAppCode($phone, $code) {
    whatsappUltraMsgVerify($phone, $code);
}

// Helper: Verify WhatsApp code (implement code storage/validation)
function verifyWhatsAppCode($phone, $code) {
    session_start();
    return (isset($_SESSION['verify_code_' . $phone]) && $_SESSION['verify_code_' . $phone] == $code);
}

// ...existing code...