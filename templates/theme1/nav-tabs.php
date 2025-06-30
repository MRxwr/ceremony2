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
    if( !empty($event["gallery"]) ){
        ?>
        <li class="nav-tab" data-panel="gallery">
        <i class="bi bi-camera"></i>
        <span class="nav-tab-label"><?php echo direction("Gallery","الصور") ?></span>
    </li>
    <?php
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