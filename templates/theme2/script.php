<script>
// Modern Theme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize loader
    const loader = document.querySelector('.loader');
    if (loader) {
        setTimeout(() => {
            loader.classList.add('hidden');
        }, 1000);
    }

    // Tab navigation with smooth transitions
    const navTabs = document.querySelectorAll('.nav-tab');
    const contentPanels = document.querySelectorAll('.content-panel');

    navTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetPanel = tab.getAttribute('data-panel');
            
            // Remove active class from all tabs and panels
            navTabs.forEach(t => t.classList.remove('active'));
            contentPanels.forEach(panel => {
                panel.classList.remove('active');
                panel.classList.add('prev');
            });
            
            // Add active class to clicked tab
            tab.classList.add('active');
            
            // Show target panel with smooth transition
            setTimeout(() => {
                const targetPanelElement = document.getElementById(targetPanel);
                if (targetPanelElement) {
                    contentPanels.forEach(panel => panel.classList.remove('prev'));
                    targetPanelElement.classList.add('active');
                }
            }, 150);
        });
    });

    // Countdown Timer
    function updateCountdown() {
        const eventDate = new Date('<?php echo $event["eventDate"]; ?>').getTime();
        const now = new Date().getTime();
        const distance = eventDate - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Update countdown display with smooth animation
            updateCountdownValue('days', days);
            updateCountdownValue('hours', hours);
            updateCountdownValue('minutes', minutes);
            updateCountdownValue('seconds', seconds);
        } else {
            // Event has passed
            document.getElementById('days').textContent = '00';
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
        }
    }

    function updateCountdownValue(elementId, value) {
        const element = document.getElementById(elementId);
        const formattedValue = value.toString().padStart(2, '0');
        
        if (element && element.textContent !== formattedValue) {
            element.style.transform = 'scale(1.1)';
            element.textContent = formattedValue;
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        }
    }

    // Update countdown every second
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // RSVP Form handling
    const rsvpForm = document.getElementById('rsvpForm');
    if (rsvpForm) {
        const attendanceSelect = rsvpForm.querySelector('select[name="attendance"]');
        const guestCountGroup = document.getElementById('guestCountGroup');

        attendanceSelect.addEventListener('change', function() {
            if (this.value === 'yes') {
                guestCountGroup.style.display = 'block';
                guestCountGroup.style.opacity = '0';
                setTimeout(() => {
                    guestCountGroup.style.opacity = '1';
                }, 50);
            } else {
                guestCountGroup.style.display = 'none';
            }
        });

        rsvpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.textContent = '<?php echo direction("Sending...","جاري الإرسال...") ?>';
            submitBtn.disabled = true;
            
            // Submit form
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('<?php echo direction("Thank you! Your RSVP has been confirmed.","شكراً لك! تم تأكيد حضورك.") ?>', 'success');
                    rsvpForm.reset();
                } else {
                    showNotification(data.message || '<?php echo direction("Something went wrong. Please try again.","حدث خطأ. يرجى المحاولة مرة أخرى.") ?>', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('<?php echo direction("Network error. Please check your connection.","خطأ في الشبكة. يرجى التحقق من الاتصال.") ?>', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Gallery modal handling
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image');
            openImageModal(imageSrc);
        });
    });

    // Floating elements animation
    createFloatingElements();

    // Smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'var(--accent-teal)' : type === 'error' ? '#E53E3E' : 'var(--light-blue)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: var(--glass-shadow);
        z-index: 10000;
        transform: translateY(-100px);
        opacity: 0;
        transition: all 0.3s ease;
        max-width: 300px;
        font-size: 0.9rem;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateY(0)';
        notification.style.opacity = '1';
    }, 100);

    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.transform = 'translateY(-100px)';
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 4000);
}

// Image modal for gallery
function openImageModal(imageSrc) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;

    const img = document.createElement('img');
    img.src = imageSrc;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        border-radius: 12px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        transform: scale(0.8);
        transition: transform 0.3s ease;
    `;

    const closeBtn = document.createElement('div');
    closeBtn.innerHTML = '×';
    closeBtn.style.cssText = `
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        cursor: pointer;
        user-select: none;
    `;

    modal.appendChild(img);
    modal.appendChild(closeBtn);
    document.body.appendChild(modal);

    // Animate in
    setTimeout(() => {
        modal.style.opacity = '1';
        img.style.transform = 'scale(1)';
    }, 50);

    // Close handlers
    const closeModal = () => {
        modal.style.opacity = '0';
        img.style.transform = 'scale(0.8)';
        setTimeout(() => {
            document.body.removeChild(modal);
        }, 300);
    };

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Keyboard support
    const handleKeyDown = (e) => {
        if (e.key === 'Escape') {
            closeModal();
            document.removeEventListener('keydown', handleKeyDown);
        }
    };
    document.addEventListener('keydown', handleKeyDown);
}

// Create floating decorative elements
function createFloatingElements() {
    const container = document.querySelector('.main-container');
    if (!container) return;

    const floatingContainer = document.createElement('div');
    floatingContainer.className = 'floating-elements';
    container.appendChild(floatingContainer);

    // Create floating dots
    for (let i = 0; i < 6; i++) {
        const dot = document.createElement('div');
        dot.className = 'floating-dot';
        dot.style.left = Math.random() * 100 + '%';
        dot.style.animationDelay = Math.random() * 12 + 's';
        dot.style.animationDuration = (8 + Math.random() * 8) + 's';
        floatingContainer.appendChild(dot);
    }
}

// Smooth transitions and performance optimizations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for smooth reveal animations
document.querySelectorAll('.countdown-item, .event-info, .gallery-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Add some festive touches
const addFestiveTouch = () => {
    const couples = document.querySelector('.couple-names');
    if (couples) {
        couples.addEventListener('mouseenter', () => {
            couples.style.transform = 'scale(1.05)';
        });
        couples.addEventListener('mouseleave', () => {
            couples.style.transform = 'scale(1)';
        });
    }
};

addFestiveTouch();
</script>
