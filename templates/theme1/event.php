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
    
    <div class="map-placeholder" style="margin-top: 2rem;">
        <?php
        // google maps full embed link get and show the map pinning 
        $location = $event["location"]; // the url from google maps
        if (!empty($location)) {
            echo "<iframe src='{$location}' width='100%' height='450' style='border:0;' allowfullscreen='' loading='lazy'></iframe>";
        } else {
            echo "<p class='text-center'>".direction("Map not available","الخريطة غير متوفرة")."</p>";
        }
        ?>
    </div>
</div>