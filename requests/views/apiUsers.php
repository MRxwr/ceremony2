
<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**

/**
 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="User Registration",
 *   description="Register a new user with phone, email, full name, password, and verification code.",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "fullName", "email", "phone", "password", "code"},
 *       @OA\Property(property="endpoint", type="string", example="register"),
 *       @OA\Property(property="fullName", type="string", example="John Doe"),
 *       @OA\Property(property="email", type="string", example="john@example.com"),
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="password", type="string", example="secret123"),
 *       @OA\Property(property="code", type="string", example="123456")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Registration response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Registration successful."))
 *     )
 *   )
 * )

 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="User Login",
 *   description="Login with phone and password to receive a bearer token.",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "phone", "password"},
 *       @OA\Property(property="endpoint", type="string", example="login"),
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="password", type="string", example="secret123")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Login response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Login successful."), @OA\Property(property="token", type="string", example="abcdef123456..."))
 *     )
 *   )
 * )

 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="Send Verification Code",
 *   description="Send a WhatsApp verification code to the user's phone.",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "phone"},
 *       @OA\Property(property="endpoint", type="string", example="sendCode"),
 *       @OA\Property(property="phone", type="string", example="1234567890")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Send code response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Verification code sent."))
 *     )
 *   )
 * )

 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="Verify Code",
 *   description="Verify the WhatsApp code sent to the user's phone.",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "phone", "code"},
 *       @OA\Property(property="endpoint", type="string", example="verifyCode"),
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="code", type="string", example="123456")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Verify code response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Code verified."))
 *     )
 *   )
 * )

 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="Forget Password",
 *   description="Send a password reset code to the user's phone via WhatsApp.",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "phone"},
 *       @OA\Property(property="endpoint", type="string", example="forgetPassword"),
 *       @OA\Property(property="phone", type="string", example="1234567890")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Forget password response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Password reset code sent."))
 *     )
 *   )
 * )

 * @OA\Post(
 *   path="/requests/index.php?a=Users",
 *   summary="Change Password",
 *   description="Change the user's password using bearer token authentication. Requires old and new password.",
 *   tags={"User"},
 *   security={{"bearerAuth":{}}},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"endpoint", "oldPassword", "newPassword"},
 *       @OA\Property(property="endpoint", type="string", example="changePassword"),
 *       @OA\Property(property="oldPassword", type="string", example="oldpass"),
 *       @OA\Property(property="newPassword", type="string", example="newpass")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Change password response",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", @OA\Property(property="msg", type="string", example="Password changed."))
 *     )
 *   )
 * )
 */

// Unified Users endpoint logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_GET['a']) && $_GET['a'] === 'Users')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $endpoint = isset($input['endpoint']) ? $input['endpoint'] : '';
    switch ($endpoint) {
        case 'sendCode':
            if (empty($input['phone'])) {
                echo outputError(["msg" => "Phone required."]);
                break;
            }
            $code = rand(100000, 999999);
            // Remove any previous code for this phone
            $del = deleteDBNew('verification_code', [$input['phone']], '`phone` = ?');
            // Insert new code
            $data = [
                'phone' => $input['phone'],
                'code' => $code
            ];
            $insertResult = insertDB('verification_code', $data);
            $whatsappResult = whatsappUltraMsgVerify($input['phone'], $code);
            if ($insertResult !== 1) {
                echo outputError(["msg" => "Failed to save code to database."]);
                break;
            }
            if (empty($whatsappResult)) {
                echo outputError(["msg" => "Failed to send WhatsApp message."]);
                break;
            }
            echo outputData(["msg" => "Verification code sent."]);
            break;
        case 'verifyCode':
            if (empty($input['phone']) || empty($input['code'])) {
                echo outputError(["msg" => "Phone and code required."]);
                break;
            }
            if (verifyWhatsAppCode($input['phone'], $input['code'])) {
                // Optionally delete the code after successful verification
                deleteDBNew('verification_code', [$input['phone']], '`phone` = ?');
                echo outputData(["msg" => "Code verified."]);
            } else {
                echo outputError(["msg" => "Invalid code."]);
            }
            break;
        case 'register':
            $required = ['fullName','email','phone','password'];
            foreach ($required as $r) {
                if (empty($input[$r])) {
                    echo outputError(["msg" => "$r required."]);
                    exit;
                }
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
            $whatsappResult = whatsappUltraMsgForgetPassword($input['phone'], $code);
            if (empty($whatsappResult)) {
                echo outputError(["msg" => "Failed to send WhatsApp password reset message."]);
                break;
            }
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

// Helper: Verify WhatsApp code (implement code storage/validation)
function verifyWhatsAppCode($phone, $code) {
    // Check verification_code table for phone/code match (and optionally, not expired)
    $row = selectDBNew('verification_code', [$phone, $code], '`phone` = ? AND `code` = ?', '');
    return ($row && isset($row[0]));
}

// ...existing code...