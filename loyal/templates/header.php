<?php
ob_start();
$directionHTML = (isset($_COOKIE["CREATEkwLANG"]) && $_COOKIE["CREATEkwLANG"] == "AR") ? "rtl" : "ltr";
$lang = (isset($_COOKIE["CREATEkwLANG"]) && $_COOKIE["CREATEkwLANG"] == "AR") ? "ar" : "en";
?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?>" dir="<?php echo $directionHTML ?>" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $settingsTitle ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#FF9F43',
                            light: '#FFB36B',
                            dark: '#E68A2E',
                        },
                        secondary: '#F8F9FA',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#FF9F43">
    <link rel="apple-touch-icon" href="img/logo.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <style>
        body {
            font-family: 'Inter', 'Noto+Sans+Arabic', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .dark .glass {
            background: rgba(31, 41, 55, 0.7);
        }
        .active-nav {
            color: #FF9F43;
        }
    </style>

    <script>
        // Dark mode initialization
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js');
            });
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen pb-20 md:pb-0 md:pt-16">

    <!-- Desktop Top Nav -->
    <nav class="hidden md:flex fixed top-0 left-0 right-0 h-16 glass border-b border-gray-200 dark:border-gray-700 z-50 items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <img src="logos/<?php echo $settingslogo ?>" alt="Logo" class="h-10 w-10 rounded-lg">
            <span class="font-bold text-xl text-primary"><?php echo $settingsTitle ?></span>
        </div>
        <div class="flex items-center gap-8">
            <a href="index.php" class="hover:text-primary transition-colors <?php echo (!isset($_GET['v']) || $_GET['v'] == 'Home') ? 'active-nav' : '' ?>"><?php echo direction("Home", "الرئيسية") ?></a>
            <a href="?v=Explore" class="hover:text-primary transition-colors <?php echo (isset($_GET['v']) && $_GET['v'] == 'Explore') ? 'active-nav' : '' ?>"><?php echo direction("Explore", "استكشف") ?></a>
            <a href="?v=Wallet" class="hover:text-primary transition-colors <?php echo (isset($_GET['v']) && $_GET['v'] == 'Wallet') ? 'active-nav' : '' ?>"><?php echo direction("Wallet", "المحفظة") ?></a>
            <a href="?v=Profile" class="hover:text-primary transition-colors <?php echo (isset($_GET['v']) && $_GET['v'] == 'Profile') ? 'active-nav' : '' ?>"><?php echo direction("Profile", "الملف الشخصي") ?></a>
            <button onclick="toggleDarkMode()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-6">
