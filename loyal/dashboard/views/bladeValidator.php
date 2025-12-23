<?php
// QR Code validator page - Dashboard integration
if (isset($_POST['qr_data']) && !empty($_POST['qr_data'])) {
    $encryptedData = trim($_POST['qr_data']);
    
    // Use the same key and IV as in the generation function
    $key = 'ceremony2024secretkeyforqrencryption12';
    $iv = 'ceremony2024iv16';
    
    $validationResult = '';
    
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
                    $validationResult = '
                        <div class="alert alert-success mt-4">
                            <h4><i class="bi bi-check-circle"></i> Valid Invitation</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Guest Name:</strong> ' . htmlspecialchars($inviteeData['name']) . '<br>
                                    <strong>Mobile:</strong> ' . htmlspecialchars($inviteeData['countryCode'] . $inviteeData['mobile']) . '<br>
                                    <strong>Attendees:</strong> ' . htmlspecialchars($inviteeData['attendees']) . '<br>
                                    <strong>Status:</strong> ';
                    
                    if ($inviteeData['isConfirmed'] == 1) {
                        $validationResult .= '<span class="badge bg-success">Confirmed</span>';
                    } else {
                        $validationResult .= '<span class="badge bg-warning">Not Confirmed</span>';
                    }
                    
                    $validationResult .= '
                                </div>
                                <div class="col-md-6">
                                    <strong>Event:</strong> ' . htmlspecialchars($eventData['title']) . '<br>
                                    <strong>Date:</strong> ' . date('F j, Y', strtotime($eventData['eventDate'])) . '<br>
                                    <strong>Time:</strong> ' . date('g:i A', strtotime($eventData['eventTime'])) . '<br>
                                    <strong>Venue:</strong> ' . htmlspecialchars($eventData['venueName'] ?? 'N/A') . '<br>
                                    <strong>Address:</strong> ' . htmlspecialchars($eventData['venueAddress'] ?? $eventData['location'] ?? 'N/A') . '
                                </div>
                            </div>';
                    
                    if (!empty($inviteeData['message'])) {
                        $validationResult .= '
                            <hr>
                            <strong>Special Requirements:</strong><br>
                            ' . htmlspecialchars($inviteeData['message']);
                    }
                    
                    $validationResult .= '</div>';
                } else {
                    $validationResult = '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Event not found.</div>';
                }
            } else {
                $validationResult = '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Invitation not found.</div>';
            }
        } else {
            $validationResult = '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Invalid QR code data.</div>';
        }
    } catch (Exception $e) {
        $validationResult = '<div class="alert alert-danger mt-4"><i class="bi bi-x-circle"></i> Error decrypting QR code: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-qr-code-scan"></i> QR Code Validator - Event Check-in System
                </h5>
                
                <!-- Camera Scanner Section -->
                <div class="camera-section" style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin: 20px 0;">
                    <h6><i class="bi bi-camera"></i> Scan QR Code with Camera</h6>
                    <p class="text-muted">Click the button below to start camera scanning</p>
                    
                    <div class="text-center mb-3">
                        <button id="start-camera" class="btn btn-success me-2">
                            <i class="bi bi-camera"></i> Start Camera
                        </button>
                        <button id="stop-camera" class="btn btn-danger" style="display: none;">
                            <i class="bi bi-camera-video-off"></i> Stop Camera
                        </button>
                    </div>
                    
                    <div id="qr-reader" style="display: none; width: 100%; max-width: 500px; margin: 0 auto;"></div>
                    <div id="scan-result" class="mt-3"></div>
                </div>

                <hr>
                
                <!-- Manual Input Section -->
                <h6><i class="bi bi-keyboard"></i> Manual Input</h6>
                <p class="text-muted">Or paste the QR code data manually</p>
                
                <form method="POST" id="manual-form">
                    <div class="mb-3">
                        <label for="qr_data" class="form-label">QR Code Data:</label>
                        <textarea class="form-control" id="qr_data" name="qr_data" rows="3" placeholder="Paste the QR code data here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Validate
                    </button>
                </form>

                <?php
                // Display validation result if available
                if (isset($validationResult)) {
                    echo $validationResult;
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Add required scripts and styles -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<style>
    #qr-reader__dashboard_section_csr button {
        margin: 5px;
    }
</style>

<script>
    let html5QrcodeScanner = null;
    
    // Function to validate QR code data
    function validateQRData(qrData) {
        // Set the data in the textarea
        document.getElementById('qr_data').value = qrData;
        
        // Submit the form programmatically
        document.getElementById('manual-form').submit();
    }
    
    // Function to start camera
    function startCamera() {
        const qrReaderDiv = document.getElementById('qr-reader');
        const startBtn = document.getElementById('start-camera');
        const stopBtn = document.getElementById('stop-camera');
        const scanResult = document.getElementById('scan-result');
        
        qrReaderDiv.style.display = 'block';
        startBtn.style.display = 'none';
        stopBtn.style.display = 'inline-block';
        
        // Clear previous results
        scanResult.innerHTML = '';
        
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
        
        // Get camera devices
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                // Use the back camera if available (usually better for QR scanning)
                let cameraId = devices[0].id;
                for (let device of devices) {
                    if (device.label.toLowerCase().includes('back') || 
                        device.label.toLowerCase().includes('rear')) {
                        cameraId = device.id;
                        break;
                    }
                }
                
                // Start scanning
                html5QrcodeScanner.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    },
                    (decodedText, decodedResult) => {
                        // Success callback
                        scanResult.innerHTML = `
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> QR Code Scanned Successfully!<br>
                                <small>Processing validation...</small>
                            </div>
                        `;
                        
                        // Stop camera and validate
                        stopCamera();
                        validateQRData(decodedText);
                    },
                    (errorMessage) => {
                        // Error callback (optional)
                        // This will be called for every frame that doesn't contain a QR code
                        // We don't need to show errors for this
                    }
                ).catch(err => {
                    console.log('Error starting camera:', err);
                    scanResult.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> Error starting camera: ${err}
                        </div>
                    `;
                    stopCamera();
                });
            } else {
                scanResult.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-camera-video-off"></i> No cameras found on this device.
                    </div>
                `;
                stopCamera();
            }
        }).catch(err => {
            console.log('Error getting cameras:', err);
            scanResult.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> Error accessing cameras: ${err}
                </div>
            `;
            stopCamera();
        });
    }
    
    // Function to stop camera
    function stopCamera() {
        const qrReaderDiv = document.getElementById('qr-reader');
        const startBtn = document.getElementById('start-camera');
        const stopBtn = document.getElementById('stop-camera');
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }).catch(err => {
                console.log('Error stopping camera:', err);
            });
        }
        
        qrReaderDiv.style.display = 'none';
        startBtn.style.display = 'inline-block';
        stopBtn.style.display = 'none';
    }
    
    // Event listeners
    document.getElementById('start-camera').addEventListener('click', startCamera);
    document.getElementById('stop-camera').addEventListener('click', stopCamera);
    
    // Stop camera when page is about to unload
    window.addEventListener('beforeunload', stopCamera);
</script>
