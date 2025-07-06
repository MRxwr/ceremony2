<div class="content-panel" id="event-panel">
    <h3 class="panel-title"><?php echo direction("{$category[0]["enTitle"]} Details","تفاصيل {$category[0]["arTitle"]}") ?></h3>
    
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-calendar-heart"></i>
            <span><?php echo direction("When","متى") ?></span>
        </div>
        <div class="info-card-content">
            <p><strong><?php echo direction("Date","التاريخ") ?>:</strong> <?php echo $event["eventDate"] ?></p>
            <p><strong><?php echo direction("Time","الوقت") ?>:</strong> <?php echo $event["eventTime"] ?></p>
        </div>
    </div>
    
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-geo-alt"></i>
            <span><?php echo direction("Where","أين") ?></span>
        </div>
        <div class="info-card-content">
            <p><strong><?php echo $event["venueName"] ?></strong></p>
            <p><?php echo $event["venueAddress"] ?></p>
        </div>
    </div>
    
    <div class="map-container">
        <?php
        $location = $event["location"]; 
        if (!empty($location)) {
            echo "<iframe src='{$location}' width='100%' height='250' style='border:0; border-radius: 16px;' allowfullscreen='' loading='lazy'></iframe>";
        } else {
            echo "<p class='text-center' style='color: var(--gray-500); padding: 2rem;'>".direction("Map not available","الخريطة غير متوفرة")."</p>";
        }
        ?>
    </div>
</div>
