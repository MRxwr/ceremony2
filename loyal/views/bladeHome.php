<!DOCTYPE html>
<html lang="en" dir="<?php echo $event["language"] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $event["title"] ?> - Join us as we celebrate our day <?php echo $event["eventDate"] ?> at <?php echo $event["venue"] ?>. We look forward to sharing this special day with you.">
    <meta name="keywords" content="wedding, <?php echo $event["title"] ?>, celebration, love, event">
    <meta property="og:title" content="<?php echo $event["title"] ?> - 7yyak.com">
    <meta property="og:description" content="Join us as we celebrate our special day">
    <meta property="og:image" content="logos/<?php echo $event["whatsappImage"] ?>">
    <meta property="og:url" content="<?php echo $event["url"] ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="7yyak.com">
    
    
    <title><?php echo $event["title"] ?> - 7yyak.com</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="logos/<?php echo $event["whatsappImage"] ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fustat:wght@200..800&display=swap');
        
        :root {
            --rose: #F8D7DA;
            --blush: #FDE2E4;
            --cream: #FFF8F3;
            --gold: #D4AF37;
            --soft-pink: #FADCD9;
            --text-dark: #2D2D2D;
            --text-light: #666666;
            --card-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--cream) 0%, var(--blush) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        [dir="rtl"] body {
            font-family: 'Fustat', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255,182,193,0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,218,185,0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255,228,225,0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }
        
        /* Main Container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        /* Wedding Card */
        .wedding-card {
            background: white;
            border-radius: 30px;
            box-shadow: var(--card-shadow);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease;
        }
        
        .wedding-card:hover {
            transform: translateY(-5px);
        }
        
        /* Card Header */
        .card-header-section {
            background: linear-gradient(135deg, var(--soft-pink) 0%, var(--blush) 100%);
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .couple-names {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .wedding-date {
            font-size: 1.2rem;
            color: var(--gold);
            font-family: 'Playfair Display', serif;
            position: relative;
            z-index: 1;
        }
        
        /* Navigation Tabs */
        .nav-tabs-custom {
            display: flex;
            justify-content: space-around;
            padding: 0;
            margin: 0;
            list-style: none;
            background: white;
            border-bottom: 1px solid var(--blush);
        }
        
        .nav-tab {
            flex: 1;
            text-align: center;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: white;
        }
        
        .nav-tab:hover {
            background: var(--cream);
        }
        
        .nav-tab.active {
            color: var(--gold);
        }
        
        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gold);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { width: 0; left: 50%; }
            to { width: 100%; left: 0; }
        }
        
        .nav-tab i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .nav-tab-label {
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        /* Content Container */
        .content-container {
            position: relative;
            height: 500px;
            overflow: hidden;
        }
        
        .content-panel {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 2rem;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }
        
        .content-panel.active {
            opacity: 1;
            transform: translateX(0);
        }
        
        .content-panel.prev {
            transform: translateX(-100%);
        }
        
        /* Countdown Timer */
        .countdown {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .countdown-item {
            background: var(--cream);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .countdown-item:hover {
            background: var(--blush);
            transform: scale(1.05);
        }
        
        .countdown-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            font-family: 'Playfair Display', serif;
        }
        
        .countdown-label {
            font-size: 0.8rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5rem;
        }
        
        /* Our Story */
        .story-photos {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .person-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--gold);
            transition: transform 0.3s ease;
        }
        
        .person-photo:hover {
            transform: scale(1.1);
        }
        
        .love-story-text {
            background: var(--cream);
            padding: 1.5rem 3.5rem 1.5rem 1.5rem;
            border-radius: 15px;
            font-style: italic;
            position: relative;
            line-height: 1.8;
        }
        
        .love-story-text::before {
            content: '"';
            font-size: 60px;
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            opacity: 0.3;
            position: absolute;
            top: -10px;
            left: 10px;
        }
        
        /* Event Details */
        .event-info {
            background: var(--cream);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }
        
        .event-info h4 {
            color: var(--gold);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .event-info i {
            font-size: 1.5rem;
        }
        
        .map-placeholder {
            background: var(--blush);
            height: 200px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Gallery */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        /* RSVP Form */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid var(--blush);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        
        .btn-submit {
            background: var(--gold);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-submit:hover {
            background: #B8941F;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
        }
        
        /* Footer */
        .card-footer-section {
            background: var(--cream);
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid var(--blush);
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .social-links a {
            color: var(--text-dark);
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--gold);
            transform: translateY(-3px);
        }
        
        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        .loader.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .heart {
            width: 50px;
            height: 45px;
            position: relative;
            animation: heartbeat 1.2s ease-in-out infinite;
        }
        
        .heart:before,
        .heart:after {
            content: '';
            width: 26px;
            height: 40px;
            position: absolute;
            left: 25px;
            top: 0;
            background: var(--gold);
            border-radius: 25px 25px 0 0;
            transform: rotate(-45deg);
            transform-origin: 0 100%;
        }
        
        .heart:after {
            left: 0;
            transform: rotate(45deg);
            transform-origin: 100% 100%;
        }
        
        @keyframes heartbeat {
            0% { transform: scale(0.95); }
            5% { transform: scale(1.1); }
            39% { transform: scale(0.85); }
            45% { transform: scale(1); }
            60% { transform: scale(0.95); }
            100% { transform: scale(0.95); }
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .couple-names {
                font-size: 2rem;
            }
            
            .nav-tab {
                padding: 0.8rem 0.5rem;
            }
            
            .nav-tab i {
                font-size: 1.2rem;
            }
            
            .nav-tab-label {
                font-size: 0.8rem;
            }
            
            .content-container {
                height: 600px;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .countdown {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Decorative Elements */
        .decorative-divider {
            width: 60px;
            height: 3px;
            background: var(--gold);
            margin: 1.5rem auto;
            border-radius: 3px;
        }
        
        .floating-hearts {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-heart {
            position: absolute;
            color: rgba(212, 175, 55, 0.3);
            animation: floatUp 15s infinite;
        }
        
        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        [dir="rtl"] .form-select {
            background-position: left 1rem center;
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="heart"></div>
    </div>
    
    <!-- Floating Hearts Background -->
    <div class="floating-hearts">
        <i class="bi bi-heart-fill floating-heart" style="left: 10%; animation-delay: 0s; font-size: 20px;"></i>
        <i class="bi bi-heart-fill floating-heart" style="left: 30%; animation-delay: 3s; font-size: 15px;"></i>
        <i class="bi bi-heart-fill floating-heart" style="left: 50%; animation-delay: 6s; font-size: 25px;"></i>
        <i class="bi bi-heart-fill floating-heart" style="left: 70%; animation-delay: 9s; font-size: 18px;"></i>
        <i class="bi bi-heart-fill floating-heart" style="left: 90%; animation-delay: 12s; font-size: 22px;"></i>
    </div>
    
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
            <div class="card-footer-section">
                <div class="social-links">
                    <a href="" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="" target="_blank"><i class="bi bi-twitter"></i></a>
                    <a href=""><i class="bi bi-envelope"></i></a>
                </div>
                <p class="text-muted mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> for your special day</p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set eventDateISO from PHP
        var eventDateISO = "<?php echo date('c', strtotime($event['eventDate'] . ' ' . $event['eventTime'])); ?>";
        
        // Hide loader
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loader = document.getElementById('loader');
                if (loader) loader.classList.add('hidden');
            }, 1000);
        });
        
        // Tab Navigation
        const tabs = document.querySelectorAll('.nav-tab');
        const panels = document.querySelectorAll('.content-panel');
        let currentPanel = 'home';
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetPanel = this.getAttribute('data-panel');
                
                if (targetPanel === currentPanel) return;
                
                // Update tabs
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Update panels
                const currentPanelEl = document.getElementById(`${currentPanel}-panel`);
                const targetPanelEl = document.getElementById(`${targetPanel}-panel`);
                
                if (currentPanelEl) {
                    currentPanelEl.classList.add('prev');
                    currentPanelEl.classList.remove('active');
                }
                if (targetPanelEl) {
                    setTimeout(() => {
                        if (currentPanelEl) currentPanelEl.classList.remove('prev');
                        targetPanelEl.classList.add('active');
                    }, 50);
                }
                
                currentPanel = targetPanel;
            });
        });
        
        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date(eventDateISO).getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;
            
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                var daysEl = document.getElementById('days');
                var hoursEl = document.getElementById('hours');
                var minutesEl = document.getElementById('minutes');
                var secondsEl = document.getElementById('seconds');
                if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
                if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
                if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
            } else {
                var countdownEl = document.getElementById('countdown');
                if (countdownEl) countdownEl.innerHTML = '<h4 class="text-center">The Wedding Day is Here!</h4>';
            }
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Form Validation and Submission
        var rsvpForm = document.getElementById('rsvpForm');
        if (rsvpForm) {
            rsvpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const fullName = formData.get('name') ? formData.get('name').trim() : '';
                const mobile1 = formData.get('mobile') ? formData.get('mobile').trim() : '';
                const guests = formData.get('attendees');
                const attendance = formData.get('isConfirmed');
                
                if (!fullName || !mobile1 || !guests || !attendance) {
                    alert('<?php echo direction("Please fill in all required fields","يرجى ملء جميع الحقول المطلوبة") ?>');
                    return;
                }

                // mobile validation all numbers min 8 and max 12
                if (mobile1 && (mobile1.length < 8 || mobile1.length > 12 || isNaN(mobile1))) {
                    alert('<?php echo direction("Please enter a valid phone number","يرجى إدخال رقم هاتف صحيح") ?>');
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = '<?php echo direction("Sending...","جاري الإرسال...") ?>';
                
                // Prepare form data for API
                const apiFormData = new FormData();
                apiFormData.append('systemCode', '<?php echo $event["code"]; ?>');
                apiFormData.append('i', '<?php echo $_GET["i"] ?? ""; ?>');
                apiFormData.append('name', fullName);
                apiFormData.append('mobile', mobile1);
                apiFormData.append('attendees', guests);
                apiFormData.append('isConfirmed', attendance);
                apiFormData.append('message', formData.get('message') || '');
                apiFormData.append('rsvp', attendance === '1' ? 'yes' : 'no');
                
                // Send to API  
                fetch('/requests/index.php?a=Rsvp', {
                    method: 'POST',
                    body: apiFormData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    // Check if response is ok and has content
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Clone the response so we can read it as text first for debugging
                    return response.clone().text().then(text => {
                        console.log('Raw response:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            console.error('Response text:', text);
                            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                    if (data.status === 'success' || data.msg === 'RSVP updated successfully.') {
                        // Success - show thank you message
                        alert('<?php echo direction("Thank you for your RSVP! We look forward to celebrating with you.","شكراً لتأكيد حضورك! نتطلع للاحتفال معك.") ?>');
                        
                        // Update the RSVP panel content to show thank you message with QR code
                        const rsvpPanel = document.getElementById('rsvp-panel');
                        if (rsvpPanel) {
                            let qrCodeSection = '';
                            if (data.qr_code) {
                                qrCodeSection = `
                                    <div class="mb-4">
                                        <h5 class="mb-3"><?php echo direction("Your Confirmation Code","رمز التأكيد الخاص بك") ?></h5>
                                        <div class="qr-code-container" style="display: inline-block; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 2px solid #e9ecef;">
                                            <img src="${data.qr_code}" alt="QR Code" style="max-width: 200px; height: auto;">
                                        </div>
                                        <p class="mt-2 text-muted small"><?php echo direction("Show this QR code at the event entrance","اعرض هذا الرمز عند مدخل الحفل") ?></p>
                                    </div>
                                `;
                            }
                            
                            rsvpPanel.innerHTML = `
                                <h3 class="text-center mb-3"><?php echo direction("RSVP","الدعوه") ?></h3>
                                <div class="decorative-divider"></div>
                                <div class="text-center">
                                    <div class="mb-4">
                                        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #28a745;"></i>
                                    </div>
                                    <h4 class="mb-3"><?php echo direction("Thank You!","شكراً لك!") ?></h4>
                                    <p class="mb-3"><?php echo direction("Thank you for your RSVP! We look forward to celebrating with you.","شكراً لتأكيد حضورك! نتطلع للاحتفال معك.") ?></p>
                                    ${qrCodeSection}
                                    <p class="mb-4"><?php echo direction("If you have any questions, please contact us.","إذا كان لديك أي استفسارات، يرجى الاتصال بنا.") ?></p>
                                    <button type="button" class="btn-submit" onclick="document.querySelector('[data-panel=&quot;home&quot;]').click();">
                                        <?php echo direction("Back to Home","العودة للصفحة الرئيسية") ?>
                                    </button>
                                </div>
                            `;
                        }
                        
                        // Optional: Add confetti effect
                        createConfetti();
                        
                    } else {
                        // Error - show error message
                        const errorMsg = data.msg || '<?php echo direction("An error occurred. Please try again.","حدث خطأ. يرجى المحاولة مرة أخرى.") ?>';
                        alert(errorMsg);
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    console.error('Error:', error);
                    alert('<?php echo direction("Network error. Please check your connection and try again.","خطأ في الشبكة. يرجى التحقق من الاتصال والمحاولة مرة أخرى.") ?>');
                });
            });
        }
        
        // Gallery lightbox (enhanced version)
        let galleryInitialized = false;
        
        function initializeGallery() {
            // Prevent multiple initializations
            if (galleryInitialized) {
                return;
            }
            
            const galleryItems = document.querySelectorAll('.gallery-item');
            if (galleryItems.length === 0) {
                return; // No gallery items found, try again later
            }
            
            galleryInitialized = true;
            
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const img = this.querySelector('img');
                    if (!img) return;
                    
                    // Check if modal already exists to prevent duplicates
                    if (document.querySelector('.gallery-modal')) {
                        return;
                    }
                    
                    // Create modal overlay
                    const modal = document.createElement('div');
                    modal.className = 'gallery-modal';
                    modal.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        cursor: pointer;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    
                    // Create image container
                    const imageContainer = document.createElement('div');
                    imageContainer.style.cssText = `
                        position: relative;
                        max-width: 90%;
                        max-height: 90%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    `;
                    
                    // Create modal image
                    const modalImg = document.createElement('img');
                    modalImg.src = img.src;
                    modalImg.alt = img.alt;
                    modalImg.style.cssText = `
                        max-width: 100%;
                        max-height: 100%;
                        border-radius: 10px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
                        transform: scale(0.8);
                        transition: transform 0.3s ease;
                    `;
                    
                    // Create close button
                    const closeBtn = document.createElement('div');
                    closeBtn.innerHTML = '&times;';
                    closeBtn.style.cssText = `
                        position: absolute;
                        top: -40px;
                        right: -10px;
                        color: white;
                        font-size: 40px;
                        font-weight: bold;
                        cursor: pointer;
                        width: 40px;
                        height: 40px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 50%;
                        background: rgba(0,0,0,0.5);
                        transition: background 0.3s ease;
                    `;
                    
                    // Add navigation if there are multiple images
                    let currentIndex = Array.from(galleryItems).indexOf(item);
                    
                    // Check if page is RTL
                    const isRTL = document.documentElement.dir === 'rtl' || document.body.dir === 'rtl' || 
                                getComputedStyle(document.documentElement).direction === 'rtl';
                    
                    if (galleryItems.length > 1) {
                        // Previous button - positioned according to reading direction
                        const prevBtn = document.createElement('div');
                        prevBtn.innerHTML = '&#10094;'; // Always left arrow for "previous"
                        prevBtn.style.cssText = `
                            position: absolute;
                            ${isRTL ? 'right' : 'left'}: 20px;
                            top: 50%;
                            transform: translateY(-50%);
                            color: white;
                            font-size: 30px;
                            font-weight: bold;
                            cursor: pointer;
                            padding: 10px;
                            border-radius: 50%;
                            background: rgba(0,0,0,0.5);
                            user-select: none;
                            transition: background 0.3s ease;
                        `;
                        
                        // Next button - positioned according to reading direction
                        const nextBtn = document.createElement('div');
                        nextBtn.innerHTML = '&#10095;'; // Always right arrow for "next"
                        nextBtn.style.cssText = `
                            position: absolute;
                            ${isRTL ? 'left' : 'right'}: 20px;
                            top: 50%;
                            transform: translateY(-50%);
                            color: white;
                            font-size: 30px;
                            font-weight: bold;
                            cursor: pointer;
                            padding: 10px;
                            border-radius: 50%;
                            background: rgba(0,0,0,0.5);
                            user-select: none;
                            transition: background 0.3s ease;
                        `;
                        
                        // Navigation functions
                        function showImage(index) {
                            const targetItem = galleryItems[index];
                            const targetImg = targetItem.querySelector('img');
                            modalImg.src = targetImg.src;
                            modalImg.alt = targetImg.alt;
                            currentIndex = index;
                        }
                        
                        // Previous/Next logic - same for both RTL and LTR
                        prevBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Previous always goes backward in sequence
                            currentIndex = currentIndex > 0 ? currentIndex - 1 : galleryItems.length - 1;
                            showImage(currentIndex);
                        });
                        
                        nextBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Next always goes forward in sequence
                            currentIndex = currentIndex < galleryItems.length - 1 ? currentIndex + 1 : 0;
                            showImage(currentIndex);
                        });
                        
                        // Keyboard navigation adjusted for RTL
                        function handleKeyPress(e) {
                            if (e.key === 'ArrowLeft') {
                                if (isRTL) {
                                    nextBtn.click(); // In RTL, left arrow should go "next" (right side button)
                                } else {
                                    prevBtn.click(); // In LTR, left arrow should go "previous" (left side button)
                                }
                            } else if (e.key === 'ArrowRight') {
                                if (isRTL) {
                                    prevBtn.click(); // In RTL, right arrow should go "previous" (right side button)
                                } else {
                                    nextBtn.click(); // In LTR, right arrow should go "next" (right side button)
                                }
                            } else if (e.key === 'Escape') {
                                closeModal();
                            }
                        }
                        
                        document.addEventListener('keydown', handleKeyPress);
                        
                        // Cleanup function for keyboard listener
                        modal.addEventListener('click', function() {
                            document.removeEventListener('keydown', handleKeyPress);
                        });
                        
                        modal.appendChild(prevBtn);
                        modal.appendChild(nextBtn);
                    }
                    
                    // Hover effects
                    closeBtn.addEventListener('mouseenter', function() {
                        this.style.background = 'rgba(255,255,255,0.2)';
                    });
                    closeBtn.addEventListener('mouseleave', function() {
                        this.style.background = 'rgba(0,0,0,0.5)';
                    });
                    
                    // Assemble modal
                    imageContainer.appendChild(modalImg);
                    imageContainer.appendChild(closeBtn);
                    modal.appendChild(imageContainer);
                    document.body.appendChild(modal);
                    
                    // Animate in
                    setTimeout(() => {
                        modal.style.opacity = '1';
                        modalImg.style.transform = 'scale(1)';
                    }, 10);
                    
                    // Close handlers
                    function closeModal() {
                        modal.style.opacity = '0';
                        modalImg.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            if (document.body.contains(modal)) {
                                document.body.removeChild(modal);
                            }
                        }, 300);
                    }
                    
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal || e.target === closeBtn) {
                            closeModal();
                        }
                    });
                    
                    closeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        closeModal();
                    });
                    
                    // Prevent image click from closing modal
                    modalImg.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });
            });
        }
        
        // Initialize gallery when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Try to initialize immediately, or wait for gallery tab
            setTimeout(initializeGallery, 500);
        });
        
        // Try to initialize when switching to gallery tab if not already initialized
        tabs.forEach(tab => {
            if (tab.getAttribute('data-panel') === 'gallery') {
                tab.addEventListener('click', function() {
                    setTimeout(function() {
                        if (!galleryInitialized) {
                            initializeGallery();
                        }
                    }, 100);
                });
            }
        });
        
        // Add floating hearts dynamically
        function createFloatingHeart() {
            const heart = document.createElement('i');
            heart.className = 'bi bi-heart-fill floating-heart';
            heart.style.left = Math.random() * 100 + '%';
            heart.style.animationDelay = Math.random() * 15 + 's';
            heart.style.fontSize = (Math.random() * 15 + 10) + 'px';
            heart.style.animationDuration = (Math.random() * 10 + 10) + 's';
            
            var heartsContainer = document.querySelector('.floating-hearts');
            if (heartsContainer) heartsContainer.appendChild(heart);
            
            // Remove heart after animation
            setTimeout(() => {
                heart.remove();
            }, 20000);
        }
        
        // Create new hearts periodically
        setInterval(createFloatingHeart, 3000);
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const activeTabs = Array.from(tabs);
            const currentIndex = activeTabs.findIndex(tab => tab.classList.contains('active'));
            
            if (e.key === 'ArrowRight' && currentIndex < activeTabs.length - 1) {
                activeTabs[currentIndex + 1].click();
            } else if (e.key === 'ArrowLeft' && currentIndex > 0) {
                activeTabs[currentIndex - 1].click();
            }
        });
        
        // Touch swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        const contentContainer = document.querySelector('.content-container');
        
        if (contentContainer) {
            contentContainer.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            contentContainer.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                const activeTabs = Array.from(tabs);
                const currentIndex = activeTabs.findIndex(tab => tab.classList.contains('active'));
                
                if (diff > 0 && currentIndex < activeTabs.length - 1) {
                    // Swipe left - next tab
                    activeTabs[currentIndex + 1].click();
                } else if (diff < 0 && currentIndex > 0) {
                    // Swipe right - previous tab
                    activeTabs[currentIndex - 1].click();
                }
            }
        }
        
        // Gallery images array from PHP
        var galleryImages = [];
        <?php
        if (isset($event['gallery']) && !empty($event['gallery'])) {
            $galleryImages = json_decode($event['gallery'], true);
            if (is_array($galleryImages) && count($galleryImages) > 0) {
                echo "galleryImages = [";
                foreach($galleryImages as $index => $image) {
                    if (!empty($image)) {
                        echo "'logos/" . addslashes($image) . "'";
                        if ($index < count($galleryImages) - 1) echo ",";
                    }
                }
                echo "];";
            }
        }
        ?>
        
        // Preload images for smooth transitions
        function preloadImages() {
            const imageUrls = [
                <?php if (!empty($event['background'])) echo "'logos/" . addslashes($event['background']) . "',"; ?>
                <?php if (!empty($event['whatsappImage'])) echo "'logos/" . addslashes($event['whatsappImage']) . "',"; ?>
            ];
            
            // Add gallery images to preload list
            if (galleryImages.length > 0) {
                imageUrls.push(...galleryImages);
            }
            
            imageUrls.forEach(url => {
                if (url && url !== '') {
                    const img = new Image();
                    img.src = url;
                }
            });
        }
        
        // Call preload when page loads
        preloadImages();
        
        
        // Add entrance animation to card
        window.addEventListener('load', function() {
            setTimeout(function() {
                var weddingCard = document.querySelector('.wedding-card');
                if (weddingCard) weddingCard.style.animation = 'fadeInUp 0.8s ease-out';
            }, 500);
        });
        
        // Enhanced confetti effect for RSVP success
        function createConfetti() {
            const colors = ['#D4AF37', '#F8D7DA', '#FDE2E4', '#FADCD9', '#FFD700', '#FF69B4', '#98FB98'];
            const confettiCount = 100;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: ${Math.random() * 15 + 5}px;
                    height: ${Math.random() * 15 + 5}px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    left: ${Math.random() * 100}%;
                    top: -20px;
                    opacity: 1;
                    transform: rotate(${Math.random() * 360}deg);
                    animation: fall ${Math.random() * 4 + 3}s linear;
                    z-index: 10000;
                    border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                `;
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    if (confetti.parentNode) {
                        confetti.remove();
                    }
                }, 7000);
            }
            
            // Add some heart confetti too
            for (let i = 0; i < 20; i++) {
                const heart = document.createElement('div');
                heart.innerHTML = '💖';
                heart.style.cssText = `
                    position: fixed;
                    font-size: ${Math.random() * 20 + 15}px;
                    left: ${Math.random() * 100}%;
                    top: -30px;
                    opacity: 1;
                    animation: fall ${Math.random() * 4 + 3}s linear;
                    z-index: 10000;
                    pointer-events: none;
                `;
                document.body.appendChild(heart);
                
                setTimeout(() => {
                    if (heart.parentNode) {
                        heart.remove();
                    }
                }, 7000);
            }
        }
        
        // Add confetti animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(720deg);
                    opacity: 0;
                }
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>