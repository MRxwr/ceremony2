<div class="content-panel active" id="home-panel">
    <h3 class="panel-title"><?php echo direction("Save the Date","إحفظ التاريخ") ?></h3>
    <p class="panel-subtitle"><?php echo direction("You are invited to join us for a special occasion.","تم دعوتك للانضمام لنا لحفل خاص") ?></p>
    
    <!-- Countdown Timer -->
    <div class="countdown-container" id="countdown">
        <div class="countdown-item">
            <div class="countdown-value" id="days">00</div>
            <div class="countdown-label"><?php echo direction("Days","أيام") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="hours">00</div>
            <div class="countdown-label"><?php echo direction("Hours","ساعات") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="minutes">00</div>
            <div class="countdown-label"><?php echo direction("Minutes","دقائق") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="seconds">00</div>
            <div class="countdown-label"><?php echo direction("Seconds","ثواني") ?></div>
        </div>
    </div>
    
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-geo-alt"></i>
            <span><?php echo direction("Venue","المكان") ?></span>
        </div>
        <div class="info-card-content">
            <p><strong><?php echo $event["venueName"] ?></strong></p>
            <p><?php echo $event["venueAddress"] ?></p>
        </div>
    </div>
</div>
