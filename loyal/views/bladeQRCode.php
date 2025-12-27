<?php
// Fetch User Data
$userID = 1; // Dummy
$user = selectDB("users", "`id` = '$userID'")[0];

// Generate QR
$qrData = generateQR($user['code']);
?>

<div class="min-h-[80vh] flex flex-col items-center justify-center space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold"><?php echo $user['firstName'] . ' ' . $user['lastName'] ?></h1>
        <p class="text-gray-500"><?php echo direction("Scan this code at the store", "امسح هذا الرمز عند المتجر") ?></p>
    </div>

    <!-- QR Container -->
    <div class="relative p-8 bg-white dark:bg-gray-800 rounded-[3rem] shadow-2xl border-8 border-primary/10">
        <div class="bg-white p-4 rounded-2xl">
            <img src="<?php echo $qrData['qr_url'] ?>" alt="QR Code" class="w-64 h-64">
        </div>
        
        <!-- Corner accents -->
        <div class="absolute -top-2 -left-2 w-12 h-12 border-t-4 border-l-4 border-primary rounded-tl-2xl"></div>
        <div class="absolute -top-2 -right-2 w-12 h-12 border-t-4 border-r-4 border-primary rounded-tr-2xl"></div>
        <div class="absolute -bottom-2 -left-2 w-12 h-12 border-b-4 border-l-4 border-primary rounded-bl-2xl"></div>
        <div class="absolute -bottom-2 -right-2 w-12 h-12 border-b-4 border-r-4 border-primary rounded-br-2xl"></div>
    </div>

    <div class="bg-gray-100 dark:bg-gray-800 px-6 py-3 rounded-2xl">
        <span class="text-xs text-gray-400 uppercase font-bold tracking-widest block text-center mb-1"><?php echo direction("User Code", "كود المستخدم") ?></span>
        <span class="text-xl font-mono font-bold text-primary"><?php echo $user['code'] ?></span>
    </div>

    <div class="flex items-center gap-3 text-sm text-gray-500 bg-white dark:bg-gray-800 px-6 py-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <i class="fas fa-info-circle text-primary"></i>
        <p><?php echo direction("Keep this code private to protect your points.", "حافظ على خصوصية هذا الكود لحماية نقاطك.") ?></p>
    </div>

    <button onclick="window.history.back()" class="text-gray-400 font-medium hover:text-primary transition-colors">
        <i class="fas fa-times mr-2"></i> <?php echo direction("Close", "إغلاق") ?>
    </button>
</div>
