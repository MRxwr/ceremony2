<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{MetaDescription}} - Join us as we celebrate our wedding on {{EventDate}}">
    <meta name="keywords" content="wedding, {{BrideName}}, {{GroomName}}, celebration, love">
    <meta property="og:title" content="{{BrideName}} & {{GroomName}} - Wedding">
    <meta property="og:description" content="Join us as we celebrate our special day">
    <meta property="og:image" content="{{OGImage}}">
    
    <title>{{BrideName}} & {{GroomName}} - Wedding</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{FaviconPath}}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
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
            padding: 1.5rem;
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
                <h1 class="couple-names">{{BrideName}} & {{GroomName}}</h1>
                <p class="wedding-date">{{EventDate}}</p>
            </div>
            
            <!-- Navigation Tabs -->
            <ul class="nav-tabs-custom">
                <li class="nav-tab active" data-panel="home">
                    <i class="bi bi-house-heart"></i>
                    <span class="nav-tab-label">Home</span>
                </li>
                <li class="nav-tab" data-panel="story">
                    <i class="bi bi-heart"></i>
                    <span class="nav-tab-label">Our Story</span>
                </li>
                <li class="nav-tab" data-panel="event">
                    <i class="bi bi-calendar-heart"></i>
                    <span class="nav-tab-label">Event</span>
                </li>
                <li class="nav-tab" data-panel="gallery">
                    <i class="bi bi-camera"></i>
                    <span class="nav-tab-label">Gallery</span>
                </li>
                <li class="nav-tab" data-panel="rsvp">
                    <i class="bi bi-envelope-heart"></i>
                    <span class="nav-tab-label">RSVP</span>
                </li>
            </ul>
            
            <!-- Content Container -->
            <div class="content-container">
                <!-- Home Panel -->
                <div class="content-panel active" id="home-panel">
                    <h3 class="text-center mb-3">Save the Date</h3>
                    <div class="decorative-divider"></div>
                    <p class="text-center text-muted mb-4">Join us as we celebrate our love and commitment</p>
                    
                    <!-- Countdown Timer -->
                    <div class="countdown" id="countdown">
                        <div class="countdown-item">
                            <div class="countdown-value" id="days">00</div>
                            <div class="countdown-label">Days</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="hours">00</div>
                            <div class="countdown-label">Hours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="minutes">00</div>
                            <div class="countdown-label">Minutes</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="seconds">00</div>
                            <div class="countdown-label">Seconds</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-2"><i class="bi bi-geo-alt text-gold"></i> {{VenueName}}</p>
                        <p class="text-muted">{{VenueAddress}}</p>
                    </div>
                </div>
                
                <!-- Our Story Panel -->
                <div class="content-panel" id="story-panel">
                    <h3 class="text-center mb-3">Our Love Story</h3>
                    <div class="decorative-divider"></div>
                    
                    <div class="story-photos">
                        <img src="{{BridePhoto}}" alt="{{BrideName}}" class="person-photo">
                        <img src="{{GroomPhoto}}" alt="{{GroomName}}" class="person-photo">
                    </div>
                    
                    <div class="love-story-text">
                        <p>{{LoveStory}}</p>
                    </div>
                </div>
                
                <!-- Event Panel -->
                <div class="content-panel" id="event-panel">
                    <h3 class="text-center mb-3">Wedding Details</h3>
                    <div class="decorative-divider"></div>
                    
                    <div class="event-info">
                        <h4><i class="bi bi-calendar-heart"></i> When</h4>
                        <p class="mb-1"><strong>Date:</strong> {{EventDate}}</p>
                        <p><strong>Time:</strong> {{EventTime}}</p>
                    </div>
                    
                    <div class="event-info">
                        <h4><i class="bi bi-geo-alt"></i> Where</h4>
                        <p class="mb-1"><strong>{{VenueName}}</strong></p>
                        <p>{{VenueAddress}}</p>
                    </div>
                    
                    <div class="map-placeholder">
                        {{MapEmbed}}
                    </div>
                </div>
                
                <!-- Gallery Panel -->
                <div class="content-panel" id="gallery-panel">
                    <h3 class="text-center mb-3">Our Memories</h3>
                    <div class="decorative-divider"></div>
                    <p class="text-center text-muted mb-3">Moments we've shared together</p>
                    
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <img src="{{GalleryImage1}}" alt="Gallery 1">
                        </div>
                        <div class="gallery-item">
                            <img src="{{GalleryImage2}}" alt="Gallery 2">
                        </div>
                        <div class="gallery-item">
                            <img src="{{GalleryImage3}}" alt="Gallery 3">
                        </div>
                        <div class="gallery-item">
                            <img src="{{GalleryImage4}}" alt="Gallery 4">
                        </div>
                        <div class="gallery-item">
                            <img src="{{GalleryImage5}}" alt="Gallery 5">
                        </div>
                        <div class="gallery-item">
                            <img src="{{GalleryImage6}}" alt="Gallery 6">
                        </div>
                    </div>
                </div>
                
                <!-- RSVP Panel -->
                <div class="content-panel" id="rsvp-panel">
                    <h3 class="text-center mb-3">RSVP</h3>
                    <div class="decorative-divider"></div>
                    
                    <form action="{{RSVPFormAction}}" method="POST" id="rsvpForm">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Full Name" name="fullName" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                        </div>
                        <div class="form-group">
                            <select class="form-select" name="guests" required>
                                <option value="">Number of Guests</option>
                                <option value="1">1 Guest</option>
                                <option value="2">2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5+">5+ Guests</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-select" name="attendance" required>
                                <option value="">Will you attend?</option>
                                <option value="yes">Joyfully Accept</option>
                                <option value="no">Regretfully Decline</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="3" placeholder="Special message or dietary requirements (optional)" name="message"></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Send RSVP</button>
                    </form>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="card-footer-section">
                <div class="social-links">
                    <a href="{{FacebookLink}}" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="{{InstagramLink}}" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="{{TwitterLink}}" target="_blank"><i class="bi bi-twitter"></i></a>
                    <a href="mailto:{{ContactEmail}}"><i class="bi bi-envelope"></i></a>
                </div>
                <p class="text-muted mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> for our special day</p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Hide loader
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
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
                
                currentPanelEl.classList.add('prev');
                currentPanelEl.classList.remove('active');
                
                setTimeout(() => {
                    currentPanelEl.classList.remove('prev');
                    targetPanelEl.classList.add('active');
                }, 50);
                
                currentPanel = targetPanel;
            });
        });
        
        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date('{{EventDateISO}}').getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;
            
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                document.getElementById('countdown').innerHTML = '<h4 class="text-center">The Wedding Day is Here!</h4>';
            }
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Form Validation
        document.getElementById('rsvpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const fullName = formData.get('fullName').trim();
            const email = formData.get('email').trim();
            const guests = formData.get('guests');
            const attendance = formData.get('attendance');
            
            if (!fullName || !email || !guests || !attendance) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Success message
            alert('Thank you for your RSVP! We look forward to celebrating with you.');
            this.reset();
            
            // Navigate back to home
            document.querySelector('[data-panel="home"]').click();
        });
        
        // Gallery lightbox (simple version)
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function() {
                const img = this.querySelector('img');
                const modal = document.createElement('div');
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
                `;
                
                const modalImg = document.createElement('img');
                modalImg.src = img.src;
                modalImg.style.cssText = `
                    max-width: 90%;
                    max-height: 90%;
                    border-radius: 10px;
                `;
                
                modal.appendChild(modalImg);
                document.body.appendChild(modal);
                
                modal.addEventListener('click', function() {
                    document.body.removeChild(modal);
                });
            });
        });
        
        // Add floating hearts dynamically
        function createFloatingHeart() {
            const heart = document.createElement('i');
            heart.className = 'bi bi-heart-fill floating-heart';
            heart.style.left = Math.random() * 100 + '%';
            heart.style.animationDelay = Math.random() * 15 + 's';
            heart.style.fontSize = (Math.random() * 15 + 10) + 'px';
            heart.style.animationDuration = (Math.random() * 10 + 10) + 's';
            
            document.querySelector('.floating-hearts').appendChild(heart);
            
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
        
        contentContainer.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        contentContainer.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
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
        
        // Preload images for smooth transitions
        function preloadImages() {
            const imageUrls = [
                '{{BridePhoto}}',
                '{{GroomPhoto}}',
                '{{GalleryImage1}}',
                '{{GalleryImage2}}',
                '{{GalleryImage3}}',
                '{{GalleryImage4}}',
                '{{GalleryImage5}}',
                '{{GalleryImage6}}',
                '{{HeroBackgroundImage}}'
            ];
            
            imageUrls.forEach(url => {
                const img = new Image();
                img.src = url;
            });
        }
        
        // Call preload when page loads
        preloadImages();
        
        // Add entrance animation to card
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.querySelector('.wedding-card').style.animation = 'fadeInUp 0.8s ease-out';
            }, 500);
        });
        
        // Optional: Add confetti effect on RSVP submission
        function createConfetti() {
            const colors = ['#D4AF37', '#F8D7DA', '#FDE2E4', '#FADCD9'];
            const confettiCount = 50;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: 10px;
                    height: 10px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    left: ${Math.random() * 100}%;
                    top: -10px;
                    opacity: 1;
                    transform: rotate(${Math.random() * 360}deg);
                    animation: fall ${Math.random() * 3 + 2}s linear;
                    z-index: 9999;
                `;
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 5000);
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