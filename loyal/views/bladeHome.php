<!-- Main Container -->
<div class="main-container">
    <div class="wedding-card">
        <!-- Card Header -->
        <div class="card-header-section">
            <h1 class="couple-names"><?php echo $event["title"]; ?></h1>
            <p class="wedding-date"><?php echo $event["eventDate"]; ?></p>
        </div>
        
        <!-- Navigation Tabs -->
        <ul class="nav-tabs-custom">
            <li class="nav-tab active" data-panel="home">
                <i class="bi bi-house-heart"></i>
                <span class="nav-tab-label"><?php echo direction("Home","الرئيسية") ?></span>
            </li>
            <li class="nav-tab" data-panel="about">
                <i class="bi bi-heart"></i>
                <span class="nav-tab-label"><?php echo direction("Highlights","الحفل") ?></span>
            </li>
            <li class="nav-tab" data-panel="event">
                <i class="bi bi-calendar-heart"></i>
                <span class="nav-tab-label"><?php echo direction("Date","الموعد") ?></span>
            </li>
            <?php 
            // Only show gallery tab if there are actual images
            if( !empty($event["gallery"]) ){
                $galleryImages = json_decode($event["gallery"], true);
                if( is_array($galleryImages) && count($galleryImages) > 0 ){
                    // Check if there's at least one non-empty image
                    $hasImages = false;
                    foreach($galleryImages as $image){
                        if(!empty($image)){
                            $hasImages = true;
                            break;
                        }
                    }
                    
                    if($hasImages){
                        ?>
                        <li class="nav-tab" data-panel="gallery">
                        <i class="bi bi-camera"></i>
                        <span class="nav-tab-label"><?php echo direction("Gallery","الصور") ?></span>
                    </li>
                    <?php
                    }
                }
            }
            if ( isset($_GET["i"]) && !empty($_GET["i"]) ) {
            ?>
            <li class="nav-tab" data-panel="rsvp">
                <i class="bi bi-envelope-heart"></i>
                <span class="nav-tab-label"><?php echo direction("RSVP","الدعوه") ?></span>
            </li>
            <?php
            }
            ?>
        </ul>
        
        <!-- Content Container -->
        <div class="content-container">
            <!-- Home Panel -->
            <div class="content-panel active" id="home-panel">
                <h3 class="text-center mb-3"><?php echo direction("Save the Date","إحفظ التاريخ") ?></h3>
                <div class="decorative-divider"></div>
                <p class="text-center text-muted mb-4"><?php echo direction("You are invited to join us for a special occasion.","تم دعوتك للانضمام لنا لحفل خاص") ?></p>
                
                <!-- Countdown Timer -->
                <div class="countdown" id="countdown">
                    <div class="countdown-item">
                        <div class="countdown-value" id="days">00</div>
                        <div class="countdown-label"><?php echo direction("Days","ايام") ?></div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">00</div>
                        <div class="countdown-label"><?php echo direction("Hours","ساعات") ?></div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">00</div>
                        <div class="countdown-label"><?php echo direction("Minutes","دقايق") ?></div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">00</div>
                        <div class="countdown-label"><?php echo direction("Seconds","ثواني") ?></div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="mb-2"><i class="bi bi-geo-alt text-gold"></i> <?php echo $event["venueName"] ?></p>
                    <p class="text-muted"><?php echo $event["venueAddress"] ?></p>
                </div>
            </div>
            
            <!-- about Panel -->
            <div class="content-panel" id="about-panel">
                <h3 class="text-center mb-3"><?php echo direction("Event Highlights","مواضيع الحفل") ?></h3>
                <div class="decorative-divider"></div>
                
                <div class="love-story-text">
                    <p><?php echo $event["details"] ?></p>
                </div>
            </div>
            
            <!-- Event Panel -->
            <div class="content-panel" id="event-panel">
                <h3 class="text-center mb-3"><?php echo direction("{$category[0]["enTitle"]} Details","تفاصيل {$category[0]["arTitle"]}") ?></h3>
                <div class="decorative-divider"></div>
                
                <div class="event-info">
                    <h4><i class="bi bi-calendar-heart"></i> <?php echo direction("When","متى") ?></h4>
                    <p class="mb-1"><strong><?php echo direction("Date","التاريخ") ?>:</strong> <?php echo $event["eventDate"] ?></p>
                    <p><strong><?php echo direction("Time","الوقت") ?>:</strong> <?php echo $event["eventTime"] ?></p>
                </div>
                
                <div class="event-info">
                    <h4><i class="bi bi-geo-alt"></i> <?php echo direction("Where","أين") ?></h4>
                    <p class="mb-1"><strong><?php echo $event["venueName"] ?></strong></p>
                    <p><?php echo $event["venueAddress"] ?></p>
                </div>
                
                <div class="map-placeholder" style="padding:20px">
                    <?php
                    $location = $event["location"]; 
                    if (!empty($location)) {
                        echo "<iframe src='{$location}' width='100%'  style='border:0; border-radius: 10px;' allowfullscreen='' loading='lazy'></iframe>";
                    } else {
                        echo "<p class='text-center'>".direction("Map not available","الخريطة غير متوفرة")."</p>";
                    }
                    ?>
                </div>
            </div>
            
            <!-- Gallery Panel -->
            <?php
            if( !empty($event["gallery"]) ){
                ?>
            <div class="content-panel" id="gallery-panel">
                <h3 class="text-center mb-3"><?php echo direction("Our Memories","ذكرياتنا") ?></h3>
                <div class="decorative-divider"></div>
                <p class="text-center text-muted mb-3"><?php echo direction("Moments we've shared together","اللحظات التي تشاركناها معاً") ?></p>
                <div class="gallery-grid">
                    <?php 
                        $galleryImages = json_decode($event["gallery"], true);
                        if( is_array($galleryImages) && count($galleryImages) > 0 ){
                            foreach($galleryImages as $index => $image){
                                if(!empty($image)){
                                    echo '<div class="gallery-item">';
                                    echo '<img src="logos/' . htmlspecialchars($image) . '" alt="Gallery ' . ($index + 1) . '">';
                                    echo '</div>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <?php
            }
            ?>
            
            <!-- RSVP Panel -->
            <?php
            if ( $invitee[0]["isConfirmed"] != 1 ){
            ?>
            <div class="content-panel" id="rsvp-panel">
                <h3 class="text-center mb-3"><?php echo direction("RSVP","الدعوه") ?></h3>
                <div class="decorative-divider"></div>
                
                <form method="POST" id="rsvpForm">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="<?php echo direction("Full Name","الاسم الكامل") ?>" pattern="[A-Za-z\s]{3,}" <?php echo ( !empty($invitee[0]["name"]) ) ? "value='{$invitee[0]["name"]}' readonly" : "" ?> required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="mobile" class="form-control" placeholder="<?php echo direction("Phone Number","رقم الهاتف") ?>" pattern="[0-9]{8,14}" <?php echo (!empty($invitee[0]["mobile"])) ? "value='{$invitee[0]["countryCode"]}{$invitee[0]["mobile"]}' readonly" : "" ?> required>
                    </div>
                    <div class="form-group">
                        <select class="form-select" name="attendees" required>
                            <option value="" selected disabled><?php echo direction("Number of Guests","عدد الحضور") ?></option>
                            <?php 
                            for ($i = 1; $i <= $invitee[0]["attendees"]; $i++) {
                                echo "<option value='{$i}'>{$i} " . direction("Guest" . ($i > 1 ? "s" : ""), ($i > 1 ? "ضيوف" : "ضيف")) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-select" name="isConfirmed" required>
                            <option value="" selected disabled ><?php echo direction("Will you attend?","سوف تحضر ؟") ?></option>
                            <option value="1"><?php echo direction("Yes","نعم") ?></option>
                            <option value="2"><?php echo direction("No","لا") ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" placeholder="<?php echo direction("Special message (optional)","رسالة خاصة ( اختياري )") ?>" name="message"></textarea>
                    </div>
                    <div class="form-group">
                        <?php echo "{$event["terms"]}"; ?>
                    </div>
                    <button type="submit" class="btn-submit"><?php echo direction("Send RSVP","ارسل الدعوه") ?></button>
                </form>
            </div>
            <?php
            }else{
                // Generate QR code for the confirmed invitee
                $qrData = generateInviteeQR($_GET["i"]);
            ?>
            <div class="content-panel" id="rsvp-panel">
                <h3 class="text-center mb-3"><?php echo direction("RSVP","الدعوه") ?></h3>
                <div class="decorative-divider"></div>
                <div class="text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #28a745;"></i>
                    </div>
                    <h4 class="mb-3"><?php echo direction("Thank You!","شكراً لك!") ?></h4>
                    <p class="mb-3"><?php echo direction("Thank you for your RSVP! We look forward to celebrating with you.","شكراً لتأكيد حضورك! نتطلع للاحتفال معك.") ?></p>
                    
                    <!-- QR Code Section -->
                    <div class="mb-4">
                        <h5 class="mb-3"><?php echo direction("Your Confirmation Code","رمز التأكيد الخاص بك") ?></h5>
                        <div class="qr-code-container" style="display: inline-block; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 2px solid #e9ecef;">
                            <img src="<?php echo $qrData['qr_url']; ?>" alt="QR Code" style="max-width: 200px; height: auto;">
                        </div>
                        <p class="mt-2 text-muted small"><?php echo direction("Show this QR code at the event entrance","اعرض هذا الرمز عند مدخل الحفل") ?></p>
                    </div>
                    
                    <p class="mb-4"><?php echo direction("If you have any questions, please contact us.","إذا كان لديك أي استفسارات، يرجى الاتصال بنا.") ?></p>
                    <button type="button" class="btn-submit" onclick="document.querySelector('[data-panel=&quot;home&quot;]').click();">
                        <?php echo direction("Back to Home","العودة للصفحة الرئيسية") ?>
                    </button>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        
        <!-- Card Footer -->
        <?php include_once "template/card-footer.php"; ?>
    </div>
</div>