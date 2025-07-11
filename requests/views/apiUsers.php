<?php
/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserRegister",
 *   summary="Register a new user (with WhatsApp phone verification)",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"fullName","email","phone","password","code"},
 *       @OA\Property(property="fullName", type="string", example="John Doe"),
 *       @OA\Property(property="email", type="string", example="john@example.com"),
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="password", type="string", example="secret123"),
 *       @OA\Property(property="code", type="string", example="123456")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Registration result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string")
 *     )
 *   )
 * )
 */
// ... User registration logic here ...

/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserSendCode",
 *   summary="Send WhatsApp code to phone for verification",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"phone"},
 *       @OA\Property(property="phone", type="string", example="1234567890")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Code sent result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string")
 *     )
 *   )
 * )
 */
// ... Send code logic here ...

/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserVerifyCode",
 *   summary="Verify WhatsApp code for phone",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"phone","code"},
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="code", type="string", example="123456")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Verification result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string")
 *     )
 *   )
 * )
 */
// ... Verify code logic here ...

/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserLogin",
 *   summary="User login (returns bearer token)",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"phone","password"},
 *       @OA\Property(property="phone", type="string", example="1234567890"),
 *       @OA\Property(property="password", type="string", example="secret123")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Login result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string"),
 *       @OA\Property(property="token", type="string")
 *     )
 *   )
 * )
 */
// ... Login logic here ...

/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserForgetPassword",
 *   summary="Request password reset (sends WhatsApp code)",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"phone"},
 *       @OA\Property(property="phone", type="string", example="1234567890")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Forget password result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string")
 *     )
 *   )
 * )
 */
// ... Forget password logic here ...

/**
 * @OA\Post(
 *   path="/requests/index.php?a=UserChangePassword",
 *   summary="Change password (requires bearer token)",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"oldPassword","newPassword"},
 *       @OA\Property(property="oldPassword", type="string", example="oldpass"),
 *       @OA\Property(property="newPassword", type="string", example="newpass")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Change password result",
 *     @OA\JsonContent(
 *       @OA\Property(property="status", type="string"),
 *       @OA\Property(property="msg", type="string")
 *     )
 *   ),
 *   security={{"bearerAuth":{}}}
 * )
 */
// ... Change password logic here ...

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
