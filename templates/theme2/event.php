<div class="content-panel" id="event">
    <div class="section-header">
        <h2 class="section-title"><?php echo direction("Event Details","تفاصيل الحدث") ?></h2>
        <p class="section-subtitle"><?php echo direction("When and where","متى وأين") ?></p>
    </div>
    
    <div class="event-info">
        <h4><i class="bi bi-calendar-event"></i> <?php echo direction("Date & Time","التاريخ والوقت") ?></h4>
        <p><?php echo $event["eventDate"]; ?></p>
        <?php if(!empty($event["eventTime"])): ?>
            <p><?php echo $event["eventTime"]; ?></p>
        <?php endif; ?>
    </div>
    
    <div class="event-info">
        <h4><i class="bi bi-geo-alt"></i> <?php echo direction("Venue","المكان") ?></h4>
        <p><?php echo !empty($event["venue"]) ? $event["venue"] : direction("Wedding Venue","مكان الزفاف"); ?></p>
        <?php if(!empty($event["address"])): ?>
            <p><?php echo $event["address"]; ?></p>
        <?php endif; ?>
    </div>
    
    <?php if(!empty($event["dresscode"])): ?>
    <div class="event-info">
        <h4><i class="bi bi-person-circle"></i> <?php echo direction("Dress Code","الزي المطلوب") ?></h4>
        <p><?php echo $event["dresscode"]; ?></p>
    </div>
    <?php endif; ?>
    
    <div class="map-placeholder">
        <i class="bi bi-map"></i>
        <?php echo direction("Interactive map will be displayed here","سيتم عرض الخريطة التفاعلية هنا") ?>
    </div>
</div>
