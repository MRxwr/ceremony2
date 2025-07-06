<?php
// Get primary color from event settings or use default
$primaryColor = !empty($event['primaryColor']) ? $event['primaryColor'] : '#6366f1';
$secondaryColor = !empty($event['secondaryColor']) ? $event['secondaryColor'] : '#ec4899';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: <?php echo $primaryColor; ?>;
    --secondary: <?php echo $secondaryColor; ?>;
    --accent: #f59e0b;
    --success: #10b981;
    --error: #ef4444;
    --warning: #f59e0b;
    --dark: #0f172a;
    --light: #ffffff;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    --gradient-accent: linear-gradient(135deg, var(--accent) 0%, #fb923c 100%);
    --gradient-dark: linear-gradient(135deg, var(--gray-800) 0%, var(--dark) 100%);
    
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    
    --border-radius: 16px;
    --border-radius-lg: 24px;
    --border-radius-xl: 32px;
    
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--gray-800);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    min-height: 100vh;
    overflow-x: hidden;
    position: relative;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Animated background particles */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
    z-index: -1;
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(5deg); }
    66% { transform: translateY(10px) rotate(-5deg); }
}

/* Main container */
.main-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    z-index: 1;
}

/* Modern card design */
.invitation-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.05);
    width: 100%;
    max-width: 420px;
    position: relative;
    overflow: hidden;
    transform: translateY(20px);
    opacity: 0;
    animation: slideInUp 0.8s ease-out 0.3s forwards;
}

@keyframes slideInUp {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Card header */
.card-header {
    padding: 2rem 2rem 1rem;
    text-align: center;
    position: relative;
    background: var(--gradient-primary);
    color: white;
    margin: -1px -1px 0 -1px;
    border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
}

.card-header::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
}

.event-title {
    font-family: 'Space Grotesk', serif;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.event-subtitle {
    font-size: 1rem;
    font-weight: 300;
    opacity: 0.9;
    margin-bottom: 1rem;
}

.couple-names {
    font-family: 'Space Grotesk', serif;
    font-size: 1.25rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.couple-names .heart-icon {
    font-size: 1rem;
    animation: heartbeat 2s ease-in-out infinite;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Navigation tabs */
.nav-tabs-container {
    padding: 0 1rem;
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.nav-tabs {
    display: flex;
    justify-content: space-around;
    padding: 0;
    margin: 0;
    list-style: none;
    position: relative;
}

.nav-tab {
    flex: 1;
    padding: 1rem 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nav-tab:hover {
    color: var(--primary);
    transform: translateY(-1px);
}

.nav-tab.active {
    color: var(--primary);
    font-weight: 600;
}

.nav-tab.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 3px;
    background: var(--gradient-primary);
    border-radius: 2px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        width: 0;
        opacity: 0;
    }
    to {
        width: 30px;
        opacity: 1;
    }
}

.nav-tab i {
    display: block;
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

/* Content container */
.content-container {
    position: relative;
    min-height: 400px;
    overflow: hidden;
}

.content-panel {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 2rem;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
}

.content-panel.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: all;
}

.content-panel.prev {
    transform: translateX(-100%);
}

/* Panel headings */
.panel-title {
    font-family: 'Space Grotesk', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
}

.panel-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: var(--gradient-primary);
    margin: 0.5rem auto;
    border-radius: 2px;
}

.panel-subtitle {
    text-align: center;
    color: var(--gray-500);
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

/* Countdown timer */
.countdown-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
    margin: 2rem 0;
}

.countdown-item {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    padding: 1rem 0.5rem;
    text-align: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.countdown-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.countdown-value {
    font-family: 'Space Grotesk', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.countdown-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Event info cards */
.info-card {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: rgba(255, 255, 255, 0.8);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: var(--primary);
    font-weight: 600;
}

.info-card-header i {
    font-size: 1.25rem;
}

.info-card-content p {
    margin-bottom: 0.5rem;
    color: var(--gray-700);
}

.info-card-content strong {
    color: var(--gray-800);
}

/* Map container */
.map-container {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    margin-top: 1rem;
    min-height: 200px;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
}

.map-container iframe {
    width: 100%;
    height: 250px;
    border: none;
}

/* Form styles */
.form-group {
    margin-bottom: 1.25rem;
}

.form-control, .form-select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--gray-800);
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    background: rgba(255, 255, 255, 0.95);
}

.form-control::placeholder {
    color: var(--gray-500);
    font-weight: 400;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Button styles */
.btn-primary {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: var(--border-radius);
    background: var(--gradient-primary);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Success state */
.success-container {
    text-align: center;
    padding: 2rem 1rem;
}

.success-icon {
    font-size: 4rem;
    color: var(--success);
    margin-bottom: 1.5rem;
    display: block;
    animation: bounceIn 0.6s ease;
}

@keyframes bounceIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.success-title {
    font-family: 'Space Grotesk', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 1rem;
}

.success-message {
    color: var(--gray-600);
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* QR Code container */
.qr-container {
    display: inline-block;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    margin: 1.5rem 0;
}

.qr-container img {
    max-width: 200px;
    height: auto;
    border-radius: var(--border-radius);
}

.qr-instructions {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin-top: 1rem;
}

/* Gallery grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}

.gallery-item {
    aspect-ratio: 1;
    border-radius: var(--border-radius);
    overflow: hidden;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow);
    position: relative;
}

.gallery-item:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-lg);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
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
    background: linear-gradient(45deg, rgba(0,0,0,0.1), rgba(255,255,255,0.1));
    opacity: 0;
    transition: var(--transition);
}

.gallery-item:hover::after {
    opacity: 1;
}

/* Love story / details */
.details-content {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    line-height: 1.7;
    color: var(--gray-700);
    font-size: 0.95rem;
    box-shadow: var(--shadow);
}

.details-content p {
    margin-bottom: 1rem;
}

.details-content p:last-child {
    margin-bottom: 0;
}

/* Loader */
.loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    transition: opacity 0.5s ease, visibility 0.5s ease;
}

.loader.hidden {
    opacity: 0;
    visibility: hidden;
}

.loader-content {
    text-align: center;
    color: white;
}

.loader-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-text {
    font-family: 'Space Grotesk', serif;
    font-size: 1.25rem;
    font-weight: 500;
}

/* RTL Support */
[dir="rtl"] .nav-tab {
    direction: rtl;
}

[dir="rtl"] .content-panel {
    transform: translateX(-100%);
}

[dir="rtl"] .content-panel.active {
    transform: translateX(0);
}

[dir="rtl"] .content-panel.prev {
    transform: translateX(100%);
}

/* Responsive design */
@media (max-width: 480px) {
    .main-container {
        padding: 10px;
    }
    
    .invitation-card {
        max-width: 100%;
        margin: 0;
    }
    
    .card-header {
        padding: 1.5rem 1rem 1rem;
    }
    
    .event-title {
        font-size: 1.5rem;
    }
    
    .content-panel {
        padding: 1.5rem;
    }
    
    .countdown-container {
        gap: 0.5rem;
    }
    
    .countdown-item {
        padding: 0.75rem 0.25rem;
    }
    
    .countdown-value {
        font-size: 1.25rem;
    }
    
    .countdown-label {
        font-size: 0.7rem;
    }
    
    .nav-tab {
        padding: 0.75rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .nav-tab i {
        font-size: 1rem;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.5rem;
    }
}

@media (max-width: 360px) {
    .event-title {
        font-size: 1.25rem;
    }
    
    .couple-names {
        font-size: 1rem;
    }
    
    .countdown-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    
    .nav-tabs {
        flex-wrap: wrap;
    }
    
    .nav-tab {
        flex: 1 1 50%;
    }
}

/* Floating elements */
.floating-hearts {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

.floating-heart {
    position: absolute;
    color: rgba(255, 255, 255, 0.6);
    animation: floatUp 15s linear infinite;
}

@keyframes floatUp {
    from {
        transform: translateY(100vh) rotate(0deg);
        opacity: 1;
    }
    to {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
    }
}

/* Accessibility */
.nav-tab:focus,
.btn-primary:focus,
.form-control:focus,
.form-select:focus {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

/* Print styles */
@media print {
    body {
        background: white !important;
    }
    
    .invitation-card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
    }
    
    .nav-tabs-container {
        display: none !important;
    }
    
    .content-panel {
        position: static !important;
        opacity: 1 !important;
        transform: none !important;
        page-break-inside: avoid;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .invitation-card {
        border: 2px solid var(--gray-800);
    }
    
    .nav-tab {
        border: 1px solid transparent;
    }
    
    .nav-tab.active {
        border-color: var(--primary);
    }
    
    .form-control, .form-select {
        border: 2px solid var(--gray-600);
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    body::before {
        animation: none;
    }
    
    .floating-heart {
        animation: none;
    }
}
</style>
