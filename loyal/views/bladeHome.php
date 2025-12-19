
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
            <?php require_once("templates/theme1/head-section.php"); ?>
            
            <!-- Navigation Tabs -->
            <?php require_once("templates/theme1/nav-tabs.php"); ?>
            
            <!-- Content Container -->
            <div class="content-container">
                <!-- Home Panel -->
                <?php require_once("templates/theme1/home.php"); ?>
                
                <!-- about Panel -->
                <?php require_once("templates/theme1/about.php"); ?>
                
                <!-- Event Panel -->
                <?php require_once("templates/theme1/event.php"); ?>
                
                <!-- Gallery Panel -->
                <?php require_once("templates/theme1/gallery.php"); ?>
                
                <!-- RSVP Panel -->
                <?php require_once("templates/theme1/rsvp.php"); ?>
            </div>
            
            <!-- Card Footer -->
            <?php require_once("templates/theme1/footer.php"); ?>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <?php require_once("templates/theme1/script.php"); ?>
</body>
</html>