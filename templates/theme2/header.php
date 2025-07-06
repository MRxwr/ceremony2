<body>
    <!-- Floating hearts container -->
    <div class="floating-hearts"></div>
    
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text"><?php echo direction("Loading...","جاري التحميل...") ?></div>
        </div>
    </div>
    
    <!-- Main container -->
    <div class="main-container">
        <!-- Modern invitation card -->
        <div class="invitation-card">
            <!-- Card header with event info -->
            <div class="card-header">
                <h1 class="event-title">
                    <?php echo direction($category[0]["enTitle"], $category[0]["arTitle"]); ?>
                </h1>
                <div class="event-subtitle">
                    <?php echo direction("You are cordially invited", "أنت مدعو بكل ود"); ?>
                </div>
                <div class="couple-names">
                    <?php 
                    $couples = explode(" & ", $event["title"]);
                    if (count($couples) >= 2) {
                        echo '<span>' . trim($couples[0]) . '</span>';
                        echo '<i class="bi bi-heart-fill heart-icon"></i>';
                        echo '<span>' . trim($couples[1]) . '</span>';
                    } else {
                        echo '<span>' . $event["title"] . '</span>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Navigation tabs -->
            <div class="nav-tabs-container">
                <div class="nav-tabs">
                    <div class="nav-tab active" data-panel="home">
                        <i class="bi bi-house-heart"></i>
                        <span><?php echo direction("Home","الرئيسية") ?></span>
                    </div>
                    <div class="nav-tab" data-panel="about">
                        <i class="bi bi-info-circle"></i>
                        <span><?php echo direction("About","حول") ?></span>
                    </div>
                    <div class="nav-tab" data-panel="event">
                        <i class="bi bi-calendar-event"></i>
                        <span><?php echo direction("Event","الحدث") ?></span>
                    </div>
                    <?php if (!empty($event["gallery"])) { ?>
                    <div class="nav-tab" data-panel="gallery">
                        <i class="bi bi-images"></i>
                        <span><?php echo direction("Gallery","معرض") ?></span>
                    </div>
                    <?php } ?>
                    <div class="nav-tab" data-panel="rsvp">
                        <i class="bi bi-reply"></i>
                        <span><?php echo direction("RSVP","الرد") ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Content container -->
            <div class="content-container">
