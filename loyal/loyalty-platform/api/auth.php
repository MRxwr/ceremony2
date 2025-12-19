<?php
header('Content-Type: application/json');
require_once("../../dashboard/includes/config.php");
require_once("../../dashboard/includes/functions.php");

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch($action) {
    case 'sendVerificationCode':
        sendVerificationCode($input['phone']);
        break;
    
    case 'verifyCode':
        verifyCode($input['phone'], $input['code']);
        break;
    
    case 'register':
        registerUser($input);
        break;
    
    case 'login':
        loginUser($input);
        break;
    
    case 'sendResetCode':
        sendResetCode($input['phone']);
        break;
    
    case 'verifyResetCode':
        verifyResetCode($input['phone'], $input['code']);
        break;
    
    case 'resetPassword':
        resetPassword($input['phone'], $input['newPassword']);
        break;
    
    default:
        echo json_encode(['ok' => false, 'message' => 'Invalid action']);
}

function sendVerificationCode($phone) {
    // Check if phone already exists
    $existing = selectDB("users", "`phone` = '{$phone}' AND `status` = '0'");
    if($existing && is_array($existing) && count($existing) > 0) {
        echo json_encode(['ok' => false, 'message' => direction('Phone number already registered', 'رقم الهاتف مسجل بالفعل')]);
        return;
    }
    
    // Generate 6-digit code
    $code = sprintf("%06d", mt_rand(1, 999999));
    
    // Store code in temporary table or session
    $existingCode = selectDB("verification_codes", "`phone` = '{$phone}'");
    if($existingCode && is_array($existingCode)) {
        updateDB('verification_codes', [
            'code' => $code,
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'attempts' => '0'
        ], "`phone` = '{$phone}'");
    } else {
        insertDB('verification_codes', [
            'phone' => $phone,
            'code' => $code,
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'attempts' => '0',
            'date' => date('Y-m-d H:i:s'),
            'status' => '0',
            'hidden' => '1'
        ]);
    }
    
    // Send WhatsApp message
    $result = whatsappUltraMsgVerify($phone, $code);
    
    echo json_encode(['ok' => true, 'message' => 'Code sent successfully']);
}

function verifyCode($phone, $code) {
    $verification = selectDB("verification_codes", "`phone` = '{$phone}' AND `status` = '0'");
    
    if(!$verification || !is_array($verification) || count($verification) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('Verification code not found', 'رمز التحقق غير موجود')]);
        return;
    }
    
    $record = $verification[0];
    
    // Check expiration
    if(strtotime($record['expiresAt']) < time()) {
        echo json_encode(['ok' => false, 'message' => direction('Code expired', 'انتهت صلاحية الرمز')]);
        return;
    }
    
    // Check attempts
    if(intval($record['attempts']) >= 5) {
        echo json_encode(['ok' => false, 'message' => direction('Too many attempts', 'محاولات كثيرة جداً')]);
        return;
    }
    
    // Verify code
    if($record['code'] === $code) {
        // Mark as verified
        updateDB('verification_codes', ['verified' => '1'], "`phone` = '{$phone}'");
        echo json_encode(['ok' => true, 'message' => 'Code verified']);
    } else {
        // Increment attempts
        updateDB('verification_codes', ['attempts' => (intval($record['attempts']) + 1)], "`phone` = '{$phone}'");
        echo json_encode(['ok' => false, 'message' => direction('Invalid code', 'رمز غير صحيح')]);
    }
}

function registerUser($data) {
    $phone = $data['phone'];
    $fullName = $data['fullName'];
    $email = $data['email'];
    $password = $data['password'];
    
    // Check if code was verified
    $verification = selectDB("verification_codes", "`phone` = '{$phone}' AND `verified` = '1' AND `status` = '0'");
    if(!$verification || !is_array($verification) || count($verification) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('Phone not verified', 'الهاتف غير محقق')]);
        return;
    }
    
    // Check if user already exists
    $existing = selectDB("users", "`phone` = '{$phone}' OR `email` = '{$email}'");
    if($existing && is_array($existing) && count($existing) > 0) {
        echo json_encode(['ok' => false, 'message' => direction('User already exists', 'المستخدم موجود بالفعل')]);
        return;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Create user
    $userId = insertDB('users', [
        'fullName' => urlencode($fullName),
        'email' => $email,
        'phone' => $phone,
        'password' => $hashedPassword,
        'date' => date('Y-m-d H:i:s'),
        'status' => '0',
        'hidden' => '1'
    ]);
    
    if($userId) {
        // Mark verification code as used
        updateDB('verification_codes', ['status' => '1'], "`phone` = '{$phone}'");
        
        // Create session
        session_start();
        $_SESSION['userId'] = $userId;
        $_SESSION['phone'] = $phone;
        $_SESSION['fullName'] = $fullName;
        
        echo json_encode(['ok' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['ok' => false, 'message' => direction('Registration failed', 'فشل التسجيل')]);
    }
}

function loginUser($data) {
    $identifier = $data['identifier'];
    $password = $data['password'];
    $rememberMe = $data['rememberMe'] ?? false;
    
    // Check if identifier is phone or email
    if(strpos($identifier, '@') !== false) {
        $user = selectDB("users", "`email` = '{$identifier}' AND `status` = '0'");
    } else {
        // Clean phone number
        $phone = preg_replace('/[^0-9+]/', '', $identifier);
        $user = selectDB("users", "`phone` = '{$phone}' AND `status` = '0'");
    }
    
    if(!$user || !is_array($user) || count($user) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('User not found', 'المستخدم غير موجود')]);
        return;
    }
    
    $userData = $user[0];
    
    // Verify password
    if(password_verify($password, $userData['password'])) {
        // Create session
        session_start();
        $_SESSION['userId'] = $userData['id'];
        $_SESSION['phone'] = $userData['phone'];
        $_SESSION['fullName'] = urldecode($userData['fullName']);
        $_SESSION['email'] = $userData['email'];
        
        // Set remember me cookie if requested
        if($rememberMe) {
            $token = bin2hex(random_bytes(32));
            setcookie('loyalty_remember', $token, time() + (86400 * 30), '/'); // 30 days
            updateDB('users', ['rememberToken' => $token], "`id` = '{$userData['id']}'");
        }
        
        echo json_encode(['ok' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['ok' => false, 'message' => direction('Invalid password', 'كلمة مرور خاطئة')]);
    }
}

function sendResetCode($phone) {
    // Check if user exists
    $user = selectDB("users", "`phone` = '{$phone}' AND `status` = '0'");
    if(!$user || !is_array($user) || count($user) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('Phone number not registered', 'رقم الهاتف غير مسجل')]);
        return;
    }
    
    // Generate 6-digit code
    $code = sprintf("%06d", mt_rand(1, 999999));
    
    // Store reset code
    $existingCode = selectDB("password_resets", "`phone` = '{$phone}'");
    if($existingCode && is_array($existingCode)) {
        updateDB('password_resets', [
            'code' => $code,
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'attempts' => '0',
            'verified' => '0'
        ], "`phone` = '{$phone}'");
    } else {
        insertDB('password_resets', [
            'phone' => $phone,
            'code' => $code,
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'attempts' => '0',
            'verified' => '0',
            'date' => date('Y-m-d H:i:s'),
            'status' => '0',
            'hidden' => '1'
        ]);
    }
    
    // Send WhatsApp message
    whatsappUltraMsgForgetPassword($phone, $code);
    
    echo json_encode(['ok' => true, 'message' => 'Reset code sent successfully']);
}

function verifyResetCode($phone, $code) {
    $reset = selectDB("password_resets", "`phone` = '{$phone}' AND `status` = '0'");
    
    if(!$reset || !is_array($reset) || count($reset) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('Reset code not found', 'رمز إعادة التعيين غير موجود')]);
        return;
    }
    
    $record = $reset[0];
    
    // Check expiration
    if(strtotime($record['expiresAt']) < time()) {
        echo json_encode(['ok' => false, 'message' => direction('Code expired', 'انتهت صلاحية الرمز')]);
        return;
    }
    
    // Check attempts
    if(intval($record['attempts']) >= 5) {
        echo json_encode(['ok' => false, 'message' => direction('Too many attempts', 'محاولات كثيرة جداً')]);
        return;
    }
    
    // Verify code
    if($record['code'] === $code) {
        // Mark as verified
        updateDB('password_resets', ['verified' => '1'], "`phone` = '{$phone}'");
        echo json_encode(['ok' => true, 'message' => 'Code verified']);
    } else {
        // Increment attempts
        updateDB('password_resets', ['attempts' => (intval($record['attempts']) + 1)], "`phone` = '{$phone}'");
        echo json_encode(['ok' => false, 'message' => direction('Invalid code', 'رمز غير صحيح')]);
    }
}

function resetPassword($phone, $newPassword) {
    // Check if reset was verified
    $reset = selectDB("password_resets", "`phone` = '{$phone}' AND `verified` = '1' AND `status` = '0'");
    if(!$reset || !is_array($reset) || count($reset) == 0) {
        echo json_encode(['ok' => false, 'message' => direction('Reset not verified', 'إعادة التعيين غير محققة')]);
        return;
    }
    
    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update user password
    if(updateDB('users', ['password' => $hashedPassword], "`phone` = '{$phone}'")) {
        // Mark reset as used
        updateDB('password_resets', ['status' => '1'], "`phone` = '{$phone}'");
        echo json_encode(['ok' => true, 'message' => 'Password reset successfully']);
    } else {
        echo json_encode(['ok' => false, 'message' => direction('Failed to reset password', 'فشل إعادة تعيين كلمة المرور')]);
    }
}
?>
