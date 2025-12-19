<?php
/**
 * @OA\Post(
 *     path="/requests/index.php?a=Rsvp",
 *     summary="RSVP to event",
 *     tags={"RSVP"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"systemCode", "i", "name", "mobile", "attendees", "isConfirmed"},
 *             @OA\Property(property="systemCode", type="string", example="ABC123"),
 *             @OA\Property(property="i", type="string", example="INVITEECODE"),
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="mobile", type="string", example="123456789"),
 *             @OA\Property(property="attendees", type="integer", example=2),
 *             @OA\Property(property="isConfirmed", type="integer", example=1),
 *             @OA\Property(property="message", type="string", example="Looking forward!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="RSVP response",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="msg", type="string", example="RSVP updated successfully."),
 *             @OA\Property(property="qr_code", type="string", example="https://yourdomain.com/qr.png")
 *         )
 *     )
 * )
 */

// Start output buffering to catch any unexpected output
ob_start();

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output, but log them

// Debug logging
error_log("RSVP API called with POST data: " . print_r($_POST, true));

// Quick test to ensure API is reachable
if (!isset($_POST["systemCode"])) {
    ob_clean(); // Clear any unexpected output
    echo json_encode(array("status" => "error", "msg" => "Missing systemCode parameter", "debug" => "API endpoint reached"));
    exit;
}

if( isset($_POST["systemCode"]) && !empty($_POST["systemCode"]) && $event = selectDBNew("events",[$_POST["systemCode"]],"`code` LIKE ? AND `hidden` = '0' AND `status` = '0'","") ){
    if( isset($_POST["i"]) && !empty($_POST["i"]) && $invitee = selectDBNew("invitees",[$_POST["i"]],"`code` LIKE ?","") ){
        if( $invitee[0]["eventId"] == $event[0]["id"] ){
            $invitee = $invitee[0];
            $updateData = [
                "isConfirmed" => $_POST["isConfirmed"],
                "attendees" => $_POST["attendees"],
            ];
            
            // Add optional message if provided
            if( isset($_POST["message"]) && !empty($_POST["message"]) ){
                $updateData["message"] = $_POST["message"];
            }
            
            if( updateDB("invitees", $updateData, "`id` = '{$invitee["id"]}'") ){
                // Generate QR code data for the response
                $qrData = generateInviteeQR($_POST["i"]);
                ob_clean(); // Clear any unexpected output
                echo json_encode(array(
                    "status" => "success", 
                    "msg" => "RSVP updated successfully.",
                    "qr_code" => $qrData['qr_url'],
                    "encrypted_data" => $qrData['encrypted']
                ));
            } else {
                echo json_encode(array("status" => "error", "msg" => "Failed to update RSVP. Please try again."));
            }
        }else{
            echo json_encode(array("status" => "error", "msg" => "Invitee is not associated to this event."));
        }
    }else{
        echo json_encode(array("status" => "error", "msg" => "Invalid invitee code."));
    }
}else{
    echo json_encode(array("status" => "error", "msg" => "Invalid event code."));
}
?>