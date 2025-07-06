<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo direction('ltr','rtl'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    
    <title><?php echo htmlspecialchars($event["title"]); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(strip_tags($event["details"])); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $currentUrl; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($event["title"]); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags($event["details"])); ?>">
    <meta property="og:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/logos/' . (!empty($event["whatsappImage"]) ? $event["whatsappImage"] : $event["background"]); ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $currentUrl; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($event["title"]); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars(strip_tags($event["details"])); ?>">
    <meta property="twitter:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/logos/' . (!empty($event["whatsappImage"]) ? $event["whatsappImage"] : $event["background"]); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/dashboard/favicon.ico">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Critical CSS for theme2 -->
    <?php include "style.php"; ?>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "<?php echo htmlspecialchars($event["title"]); ?>",
        "description": "<?php echo htmlspecialchars(strip_tags($event["details"])); ?>",
        "startDate": "<?php echo date('c', strtotime($event['eventDate'] . ' ' . $event['eventTime'])); ?>",
        "location": {
            "@type": "Place",
            "name": "<?php echo htmlspecialchars($event["venueName"]); ?>",
            "address": "<?php echo htmlspecialchars($event["venueAddress"]); ?>"
        },
        "image": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/logos/' . (!empty($event["whatsappImage"]) ? $event["whatsappImage"] : $event["background"]); ?>",
        "organizer": {
            "@type": "Organization",
            "name": "7yyak Digital Invitations"
        }
    }
    </script>
</head>
