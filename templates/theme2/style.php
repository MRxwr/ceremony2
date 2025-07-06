<style>
    @import url('https://fonts.googleapis.com/css2?family=Fustat:wght@200..800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap');
    
    :root {
        --primary-blue: #2C5282;
        --light-blue: #4299E1;
        --navy: #1A365D;
        --sky-blue: #E6F3FF;
        --soft-white: #FAFAFA;
        --pure-white: #FFFFFF;
        --light-gray: #F7FAFC;
        --text-dark: #1A202C;
        --text-medium: #4A5568;
        --text-light: #718096;
        --accent-teal: #38B2AC;
        --card-shadow: 0 25px 80px rgba(44, 82, 130, 0.12);
        --glass-shadow: 0 8px 32px rgba(44, 82, 130, 0.1);
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
        background: linear-gradient(135deg, var(--soft-white) 0%, var(--sky-blue) 100%);
        min-height: 100vh;
        overflow-x: hidden;
        line-height: 1.6;
    }
    
    [dir="rtl"] body {
        font-family: 'Fustat', sans-serif;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Dancing Script', cursive;
        font-weight: 600;
        color: var(--navy);
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
            radial-gradient(circle at 10% 20%, rgba(66, 153, 225, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 90% 80%, rgba(56, 178, 172, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(44, 82, 130, 0.05) 0%, transparent 50%);
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
    
    /* Wedding Card - Modern Glass Morphism */
    .wedding-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        max-width: 480px;
        width: 100%;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .wedding-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 35px 100px rgba(44, 82, 130, 0.15);
    }
    
    /* Card Header - Minimalist Design */
    .card-header-section {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 100%);
        padding: 3.5rem 2rem 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .card-header-section::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent, rgba(255,255,255,0.1));
        border-radius: 20px;
        z-index: 1;
    }
    
    .card-header-section::after {
        content: '♥';
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 24px;
        color: rgba(255, 255, 255, 0.3);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }
    
    .couple-names {
        font-size: 2.8rem;
        color: white;
        margin-bottom: 0.8rem;
        position: relative;
        z-index: 2;
        font-weight: 700;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .wedding-date {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        font-family: 'Poppins', sans-serif;
        font-weight: 300;
        position: relative;
        z-index: 2;
        letter-spacing: 1px;
    }
    
    /* Navigation Tabs - Modern Flat Design */
    .nav-tabs-custom {
        display: flex;
        justify-content: space-around;
        padding: 0;
        margin: 0;
        list-style: none;
        background: var(--pure-white);
        border-bottom: 1px solid rgba(44, 82, 130, 0.1);
    }
    
    .nav-tab {
        flex: 1;
        text-align: center;
        padding: 1.2rem 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        background: transparent;
        color: var(--text-medium);
    }
    
    .nav-tab:hover {
        background: var(--light-gray);
        color: var(--primary-blue);
    }
    
    .nav-tab.active {
        color: var(--primary-blue);
        background: var(--sky-blue);
    }
    
    .nav-tab.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-blue), var(--light-blue));
        animation: slideIn 0.4s ease;
    }
    
    @keyframes slideIn {
        from { width: 0; left: 50%; }
        to { width: 100%; left: 0; }
    }
    
    .nav-tab i {
        display: block;
        font-size: 1.4rem;
        margin-bottom: 0.4rem;
    }
    
    .nav-tab-label {
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Content Container */
    .content-container {
        position: relative;
        height: 520px;
        overflow: hidden;
        background: var(--pure-white);
    }
    
    .content-panel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 2.5rem 2rem;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        overflow-y: auto;
    }
    
    .content-panel.active {
        opacity: 1;
        transform: translateX(0);
    }
    
    .content-panel.prev {
        transform: translateX(-100%);
    }
    
    /* Countdown Timer - Clean Cards */
    .countdown {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
        margin-top: 2rem;
    }
    
    .countdown-item {
        background: var(--light-gray);
        padding: 1.8rem 1rem;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(44, 82, 130, 0.08);
    }
    
    .countdown-item:hover {
        background: var(--sky-blue);
        transform: translateY(-3px);
        box-shadow: var(--glass-shadow);
    }
    
    .countdown-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-blue);
        font-family: 'Poppins', sans-serif;
    }
    
    .countdown-label {
        font-size: 0.75rem;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    /* Our Story - Modern Card */
    .story-photos {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        margin-bottom: 2rem;
    }
    
    .person-photo {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--light-blue);
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(44, 82, 130, 0.15);
    }
    
    .person-photo:hover {
        transform: scale(1.08);
        border-color: var(--accent-teal);
        box-shadow: 0 15px 40px rgba(56, 178, 172, 0.2);
    }
    
    .love-story-text {
        background: var(--light-gray);
        padding: 2rem;
        border-radius: 15px;
        font-style: italic;
        position: relative;
        line-height: 1.8;
        border-left: 4px solid var(--light-blue);
        color: var(--text-medium);
    }
    
    .love-story-text::before {
        content: '"';
        font-size: 50px;
        font-family: 'Dancing Script', cursive;
        color: var(--light-blue);
        opacity: 0.3;
        position: absolute;
        top: 10px;
        left: 15px;
    }
    
    /* Event Details - Clean Layout */
    .event-info {
        background: var(--light-gray);
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-blue);
    }
    
    .event-info h4 {
        color: var(--primary-blue);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .event-info i {
        font-size: 1.3rem;
        color: var(--light-blue);
    }
    
    .map-placeholder {
        background: var(--sky-blue);
        height: 180px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-medium);
        font-size: 0.9rem;
        border: 1px solid rgba(44, 82, 130, 0.1);
    }
    
    /* Gallery - Modern Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
        margin-top: 1.5rem;
    }
    
    .gallery-item {
        aspect-ratio: 1;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 15px rgba(44, 82, 130, 0.1);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(44, 82, 130, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover::after {
        opacity: 1;
    }
    
    /* RSVP Form - Modern Clean */
    .form-group {
        margin-bottom: 1.8rem;
    }
    
    .form-control, .form-select {
        border: 2px solid rgba(44, 82, 130, 0.1);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--pure-white);
        font-family: 'Poppins', sans-serif;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--light-blue);
        box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.15);
        outline: none;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 25px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
    }
    
    .btn-submit:hover {
        background: linear-gradient(135deg, var(--navy), var(--primary-blue));
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 82, 130, 0.3);
    }
    
    /* Footer - Minimal */
    .card-footer-section {
        background: var(--light-gray);
        padding: 1.8rem;
        text-align: center;
        border-top: 1px solid rgba(44, 82, 130, 0.1);
    }
    
    .social-links {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1rem;
    }
    
    .social-links a {
        color: var(--text-medium);
        font-size: 1.4rem;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 50%;
    }
    
    .social-links a:hover {
        color: var(--light-blue);
        background: rgba(66, 153, 225, 0.1);
        transform: translateY(-3px);
    }
    
    /* Loading Animation - Modern */
    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--pure-white);
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
    
    .modern-loader {
        width: 60px;
        height: 60px;
        position: relative;
    }
    
    .modern-loader::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 4px solid rgba(44, 82, 130, 0.1);
        border-top: 4px solid var(--light-blue);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .couple-names {
            font-size: 2.2rem;
        }
        
        .nav-tab {
            padding: 1rem 0.3rem;
        }
        
        .nav-tab i {
            font-size: 1.2rem;
        }
        
        .nav-tab-label {
            font-size: 0.75rem;
        }
        
        .content-container {
            height: 550px;
        }
        
        .content-panel {
            padding: 2rem 1.5rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .countdown {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .story-photos {
            gap: 1.5rem;
        }
        
        .person-photo {
            width: 90px;
            height: 90px;
        }
    }
    
    /* Decorative Elements */
    .decorative-divider {
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-blue), var(--light-blue));
        margin: 2rem auto;
        border-radius: 3px;
    }
    
    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }
    
    .floating-dot {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--light-blue);
        border-radius: 50%;
        opacity: 0.6;
        animation: floatUpDots 12s infinite;
    }
    
    @keyframes floatUpDots {
        0% {
            transform: translateY(100vh) translateX(0);
            opacity: 0;
        }
        10% {
            opacity: 0.6;
        }
        90% {
            opacity: 0.6;
        }
        100% {
            transform: translateY(-100vh) translateX(50px);
            opacity: 0;
        }
    }
    
    /* Section Headers */
    .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.8rem;
        color: var(--navy);
        margin-bottom: 0.5rem;
    }
    
    .section-subtitle {
        color: var(--text-light);
        font-size: 0.9rem;
        font-weight: 300;
    }
    
    /* RTL Support */
    [dir="rtl"] .form-select {
        background-position: left 1rem center;
        text-align: right;
    }
    
    [dir="rtl"] .event-info {
        border-left: none;
        border-right: 4px solid var(--primary-blue);
    }
    
    [dir="rtl"] .love-story-text {
        border-left: none;
        border-right: 4px solid var(--light-blue);
    }
    
    [dir="rtl"] .love-story-text::before {
        left: auto;
        right: 15px;
    }
    
    /* Smooth transitions for better UX */
    * {
        transition: color 0.2s ease, background-color 0.2s ease;
    }
    
    /* Focus states for accessibility */
    .nav-tab:focus {
        outline: 2px solid var(--light-blue);
        outline-offset: 2px;
    }
    
    .btn-submit:focus {
        outline: 2px solid var(--light-blue);
        outline-offset: 2px;
    }
</style>
