<?php
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");

// QR Code validator page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Validator - Event Check-in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-qr-code-scan"></i> Event Check-in System
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="qr_data" class="form-label">Scan QR Code or Enter Data:</label>
                                <textarea class="form-control" id="qr_data" name="qr_data" rows="3" placeholder="Paste the QR code data here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Validate
                            </button>
                        </form>

                        <?php
                        if (isset($_POST['qr_data']) && !empty($_POST['qr_data'])) {
                            $encryptedData = trim($_POST['qr_data']);
                            
                            // Use the same key and IV as in the generation function
                            $key = 'ceremony2024secretkeyforqrencryption12';
                            $iv = 'ceremony2024iv16';
                            
                            try {
                                $decryptedInviteeCode = decryptData($encryptedData, $key, $iv);
                                
                                if ($decryptedInviteeCode) {
                                    // Look up the invitee
                                    $invitee = selectDBNew("invitees", [$decryptedInviteeCode], "`code` LIKE ?", "");
                                    
                                    if ($invitee && count($invitee) > 0) {
                                        $inviteeData = $invitee[0];
                                        $event = selectDB("events", "`id` = '{$inviteeData['eventId']}'");
                                        
                                        if ($event && count($event) > 0) {
                                            $eventData = $event[0];
                                            ?>
                                            <div class="alert alert-success mt-4">
                                                <h4><i class="bi bi-check-circle"></i> Valid Invitation</h4>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Guest Name:</strong> <?php echo htmlspecialchars($inviteeData['name']); ?><br>
                                                        <strong>Mobile:</strong> <?php echo htmlspecialchars($inviteeData['countryCode'] . $inviteeData['mobile']); ?><br>
                                                        <strong>Attendees:</strong> <?php echo htmlspecialchars($inviteeData['attendees']); ?><br>
                                                        <strong>Status:</strong> 
                                                        <?php 
                                                        if ($inviteeData['isConfirmed'] == 1) {
                                                            echo '<span class="badge bg-success">Confirmed</span>';
                                                        } else {
                                                            echo '<span class="badge bg-warning">Not Confirmed</span>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Event:</strong> <?php echo htmlspecialchars($eventData['title']); ?><br>
                                                        <strong>Date:</strong> <?php echo date('F j, Y', strtotime($eventData['eventDate'])); ?><br>
                                                        <strong>Time:</strong> <?php echo date('g:i A', strtotime($eventData['eventTime'])); ?><br>
                                                        <strong>Location:</strong> <?php echo htmlspecialchars($eventData['location']); ?>
                                                    </div>
                                                </div>
                                                <?php if (!empty($inviteeData['message'])): ?>
                                                <hr>
                                                <strong>Special Requirements:</strong><br>
                                                <?php echo htmlspecialchars($inviteeData['message']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                        } else {
                                            echo '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Event not found.</div>';
                                        }
                                    } else {
                                        echo '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Invitation not found.</div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Invalid QR code data.</div>';
                                }
                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Error decrypting QR code: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
