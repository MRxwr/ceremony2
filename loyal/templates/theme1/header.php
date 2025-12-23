<!DOCTYPE html>
<html lang="en" dir="<?php echo $event["language"] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $event["title"] ?> - Join us as we celebrate our day <?php echo $event["eventDate"] ?> at <?php echo $event["venue"] ?>. We look forward to sharing this special day with you.">
    <meta name="keywords" content="wedding, <?php echo $event["title"] ?>, celebration, love, event">
    <meta property="og:title" content="<?php echo $event["title"] ?> - 7yyak.com">
    <meta property="og:description" content="Join us as we celebrate our special day">
    <meta property="og:image" content="logos/<?php echo $event["whatsappImage"] ?>">
    <meta property="og:url" content="<?php echo $event["url"] ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="7yyak.com">
    
    
    <title><?php echo $event["title"] ?> - 7yyak.com</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="logos/<?php echo $event["whatsappImage"] ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <?php require_once("templates/theme1/style.php"); ?>
</head>