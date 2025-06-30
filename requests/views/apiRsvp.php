<?php 
if( isset($_POST["systemCode"]) && !empty($_POST["systemCode"]) && $event = selectDBNew("events",[$_POST["systemCode"]],"`code` LIKE ? AND `hidden` = '0' AND `status` = '0'","") ){
    if( isset($_POST["i"]) && !empty($_POST["i"]) && $invitee = selectDBNew("invitees",[$_POST["i"]],"`code` LIKE ?","") ){
        if( $invitee[0]["eventId"] == $event[0]["id"] ){
            $invitee = $invitee[0];
            if( isset($_POST["rsvp"]) && in_array($_POST["rsvp"], ["yes", "no", "maybe"]) ){
                $updateData = [
                    "isConfirmed" => $_POST["isConfirmed"],
                    "attendees" => $_POST["attendees"],
                ];
                
                // Add optional message if provided
                if( isset($_POST["message"]) && !empty($_POST["message"]) ){
                    $updateData["message"] = $_POST["message"];
                }
                
                if( updateDB("invitees", $updateData, "`id` = '{$invitee["id"]}'") ){
                    echo json_encode(array("status" => "success", "msg" => "RSVP updated successfully."));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "Failed to update RSVP. Please try again."));
                }
            }else{
                echo json_encode(array("status" => "error", "msg" => "Invalid RSVP value."));
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