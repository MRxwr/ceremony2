
    <!-- Include theme2 header with navigation and structure -->
    <?php require_once("templates/theme2/header.php"); ?>
    
    <!-- Content panels -->
    <!-- Home Panel -->
    <?php require_once("templates/theme2/home.php"); ?>
    
    <!-- About Panel -->
    <?php require_once("templates/theme2/about.php"); ?>
    
    <!-- Event Panel -->
    <?php require_once("templates/theme2/event.php"); ?>
    
    <!-- Gallery Panel -->
    <?php if (!empty($event["gallery"])) require_once("templates/theme2/gallery.php"); ?>
    
    <!-- RSVP Panel -->
    <?php require_once("templates/theme2/rsvp.php"); ?>
    
    <!-- Include theme2 footer and scripts -->
    <?php require_once("templates/theme2/footer.php"); ?>
    
    <!-- Include theme2 JavaScript -->
    <?php require_once("templates/theme2/script.php"); ?>