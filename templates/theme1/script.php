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
    
    // Form Validation
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
                alert('Please fill in all required fields');
                return;
            }

            // mobile validation all mubers min 8 and most 12
            if (mobile1 && (mobile1.length < 8 || mobile1.length > 12 || isNaN(mobile1))) {
                alert('Please enter a valid phone number');
                return;
            }
            
            // Success message
            alert('Thank you for your RSVP! We look forward to celebrating with you.');
            this.reset();
            
            // Navigate back to home
            var homeTab = document.querySelector('[data-panel="home"]');
            if (homeTab) homeTab.click();
        });
    }
    
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
            var weddingCard = document.querySelector('.wedding-card');
            if (weddingCard) weddingCard.style.animation = 'fadeInUp 0.8s ease-out';
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