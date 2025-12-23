<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7yyak - Beautiful Digital Wedding Invitations</title>
    <meta name="description" content="Create stunning digital wedding invitations with RSVP tracking, QR codes, and beautiful galleries. Modern, elegant, and easy to manage.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #D4AF37;
            --secondary-color: #8B4513;
            --accent-color: #FFF8DC;
            --text-dark: #2C3E50;
            --text-light: #6C757D;
            --gradient-1: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-2: linear-gradient(135deg, #FFF8DC 0%, #F5F5DC 100%);
            --shadow-soft: 0 10px 40px rgba(212, 175, 55, 0.1);
            --shadow-medium: 0 15px 50px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hearts" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><text x="10" y="15" text-anchor="middle" font-size="12" fill="%23D4AF37" opacity="0.1">♥</text></pattern></defs><rect width="100" height="100" fill="url(%23hearts)"/></svg>');
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero .lead {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .btn-primary-custom {
            background: var(--gradient-1);
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 50px rgba(212, 175, 55, 0.3);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: white;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 175, 55, 0.1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2rem;
            color: white;
        }

        .feature-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 100px 0;
            background: var(--gradient-2);
        }

        .step-card {
            text-align: center;
            padding: 30px 20px;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .step-card h5 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        /* Testimonials */
        .testimonials {
            padding: 100px 0;
            background: white;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .stars {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: var(--gradient-1);
            color: white;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .btn-white {
            background: white;
            color: var(--primary-color);
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
            color: var(--primary-color);
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .footer a {
            color: #BDC3C7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero .lead {
                font-size: 1.1rem;
            }
            
            .btn-primary-custom,
            .btn-outline-custom {
                padding: 12px 30px;
                font-size: 1rem;
            }
            
            .feature-card {
                padding: 30px 20px;
                margin-bottom: 30px;
            }
        }

        /* Animations */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand font-display fw-bold fs-3" href="#" style="color: var(--primary-color);">
                <i class="bi bi-heart-fill me-2"></i>7yyak
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-custom ms-3" href="dashboard/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <h1 class="font-display">Create Beautiful Digital Wedding Invitations</h1>
                    <p class="lead">Design stunning, interactive wedding invitations with RSVP tracking, photo galleries, and QR code check-ins. Make your special day unforgettable.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="dashboard/" class="btn-primary-custom">Start Creating</a>
                        <a href="#features" class="btn-outline-custom">Learn More</a>
                    </div>
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Free to start
                            <i class="bi bi-check-circle-fill text-success me-2 ms-3"></i>No credit card required
                        </small>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="floating">
                        <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Wedding Invitation Preview" 
                             class="img-fluid rounded-4 shadow-lg" 
                             style="max-width: 400px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="font-display fw-bold mb-3" style="font-size: 2.5rem;">Everything You Need for Perfect Invitations</h2>
                    <p class="lead text-muted">Our platform provides all the tools to create, manage, and track your wedding invitations effortlessly.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h4>Beautiful Designs</h4>
                        <p>Choose from elegant, customizable templates that match your wedding theme. RTL/LTR support for multiple languages.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h4>RSVP Management</h4>
                        <p>Track responses effortlessly with built-in RSVP forms, guest count management, and dietary requirements tracking.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-images"></i>
                        </div>
                        <h4>Photo Gallery</h4>
                        <p>Share your love story with beautiful photo galleries, engagement photos, and memories with elegant lightbox viewing.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-qr-code"></i>
                        </div>
                        <h4>QR Code Check-in</h4>
                        <p>Modern check-in system with encrypted QR codes for seamless guest verification at your venue entrance.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Mobile Responsive</h4>
                        <p>Perfect viewing experience on all devices - desktop, tablet, and mobile. Your guests can RSVP from anywhere.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Event Details</h4>
                        <p>Share all important information - venue details, timing, dress code, and interactive maps for easy navigation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="font-display fw-bold mb-3" style="font-size: 2.5rem;">How It Works</h2>
                    <p class="lead text-muted">Create your perfect wedding invitation in just a few simple steps.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>Sign Up</h5>
                        <p class="text-muted">Create your account and access the dashboard to start building your invitation.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Customize</h5>
                        <p class="text-muted">Add your details, photos, and customize the design to match your wedding theme.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Send Invites</h5>
                        <p class="text-muted">Share your beautiful invitation link with guests via WhatsApp, email, or social media.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5>Track RSVPs</h5>
                        <p class="text-muted">Monitor responses, manage guest lists, and check guests in with QR codes on your wedding day.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="font-display fw-bold mb-3" style="font-size: 2.5rem;">What Couples Say About Us</h2>
                    <p class="lead text-muted">Join thousands of happy couples who made their special day memorable.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <i class="bi bi-person-hearts"></i>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"Absolutely beautiful! The QR code check-in made our wedding so organized. Our guests loved the interactive invitation."</p>
                        <strong>Sarah & Ahmed</strong>
                        <small class="text-muted d-block">Dubai Wedding</small>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <i class="bi bi-heart-eyes"></i>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"The RSVP tracking saved us so much time! The design was perfect for our traditional ceremony. Highly recommended!"</p>
                        <strong>Fatima & Omar</strong>
                        <small class="text-muted d-block">Riyadh Wedding</small>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"Easy to use, beautiful designs, and the photo gallery feature let us share our journey with everyone. Perfect!"</p>
                        <strong>Layla & Khalid</strong>
                        <small class="text-muted d-block">Jeddah Wedding</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="font-display">Ready to Create Your Perfect Invitation?</h2>
                    <p>Join thousands of couples who chose 7yyak for their special day. Start creating your beautiful invitation today!</p>
                    <a href="dashboard/" class="btn-white pulse">Start Your Free Invitation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="font-display">
                        <i class="bi bi-heart-fill me-2"></i>7yyak
                    </h5>
                    <p class="text-muted">Creating beautiful digital wedding invitations that make your special day unforgettable.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Features</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features">Design Templates</a></li>
                        <li><a href="#features">RSVP Management</a></li>
                        <li><a href="#features">Photo Gallery</a></li>
                        <li><a href="#features">QR Code Check-in</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Video Tutorials</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Account</h5>
                    <ul class="list-unstyled">
                        <li><a href="dashboard/">Dashboard</a></li>
                        <li><a href="dashboard/login.php">Login</a></li>
                        <li><a href="dashboard/">Sign Up</a></li>
                        <li><a href="qr-validator.php">QR Validator</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: #495057;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">&copy; 2025 7yyak. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">Made with <i class="bi bi-heart-fill text-danger"></i> for happy couples</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'white';
                navbar.style.backdropFilter = 'none';
            }
        });

        // Add floating animation to feature cards on hover
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>