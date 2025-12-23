<?php 
// Default API response for unsupported endpoints
header('Content-Type: application/json');
echo json_encode(array("status" => "error", "msg" => "API endpoint not found."));
?>
