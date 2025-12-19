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