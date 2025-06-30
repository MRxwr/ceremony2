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
    
    [dir="rtl"] .form-select {
        background-position: left 1rem center;
        text-align: right;
    }
</style>