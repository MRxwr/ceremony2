
<body>
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="modern-loader"></div>
    </div>
    
    <!-- Floating Elements Background -->
    <div class="floating-elements">
        <div class="floating-dot" style="left: 10%; animation-delay: 0s;"></div>
        <div class="floating-dot" style="left: 30%; animation-delay: 3s;"></div>
        <div class="floating-dot" style="left: 50%; animation-delay: 6s;"></div>
        <div class="floating-dot" style="left: 70%; animation-delay: 9s;"></div>
        <div class="floating-dot" style="left: 90%; animation-delay: 12s;"></div>
    </div>
    
    <!-- Main Container -->
    <div class="main-container">
        <div class="wedding-card">
            <!-- Card Header -->
            <?php require_once("templates/theme2/head-section.php"); ?>
            
            <!-- Navigation Tabs -->
            <?php require_once("templates/theme2/nav-tabs.php"); ?>
            
            <!-- Content Container -->
            <div class="content-container">
                <!-- Home Panel -->
                <?php require_once("templates/theme2/home.php"); ?>
                
                <!-- about Panel -->
                <?php require_once("templates/theme2/about.php"); ?>
                
                <!-- Event Panel -->
                <?php require_once("templates/theme2/event.php"); ?>
                
                <!-- Gallery Panel -->
                <?php require_once("templates/theme2/gallery.php"); ?>
                
                <!-- RSVP Panel -->
                <?php require_once("templates/theme2/rsvp.php"); ?>
            </div>
            
            <!-- Card Footer -->
            <?php require_once("templates/theme2/footer.php"); ?>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <?php require_once("templates/theme2/script.php"); ?>
</body>
</html>