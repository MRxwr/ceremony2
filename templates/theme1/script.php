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
                    // Previous button (left arrow for LTR, right arrow for RTL)
                    const prevBtn = document.createElement('div');
                    prevBtn.innerHTML = isRTL ? '&#10095;' : '&#10094;';
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
                    
                    // Next button (right arrow for LTR, left arrow for RTL)
                    const nextBtn = document.createElement('div');
                    nextBtn.innerHTML = isRTL ? '&#10094;' : '&#10095;';
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
                    
                    // Previous/Next logic adjusted for RTL
                    prevBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (isRTL) {
                            // In RTL, "previous" means go to next image
                            currentIndex = currentIndex < galleryItems.length - 1 ? currentIndex + 1 : 0;
                        } else {
                            // In LTR, "previous" means go to previous image
                            currentIndex = currentIndex > 0 ? currentIndex - 1 : galleryItems.length - 1;
                        }
                        showImage(currentIndex);
                    });
                    
                    nextBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (isRTL) {
                            // In RTL, "next" means go to previous image
                            currentIndex = currentIndex > 0 ? currentIndex - 1 : galleryItems.length - 1;
                        } else {
                            // In LTR, "next" means go to next image
                            currentIndex = currentIndex < galleryItems.length - 1 ? currentIndex + 1 : 0;
                        }
                        showImage(currentIndex);
                    });
                    
                    // Keyboard navigation adjusted for RTL
                    function handleKeyPress(e) {
                        if (e.key === 'ArrowLeft') {
                            if (isRTL) {
                                nextBtn.click(); // In RTL, left arrow should go "next"
                            } else {
                                prevBtn.click(); // In LTR, left arrow should go "previous"
                            }
                        } else if (e.key === 'ArrowRight') {
                            if (isRTL) {
                                prevBtn.click(); // In RTL, right arrow should go "previous"
                            } else {
                                nextBtn.click(); // In LTR, right arrow should go "next"
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