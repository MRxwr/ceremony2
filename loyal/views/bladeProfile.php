<?php
// Fetch User Data
$userID = 1; // Dummy
$user = selectDB("users", "`id` = '$userID'")[0];

// Fetch total scans
$scansCount = queryDB("SELECT SUM(collectedPoints + collectedStamps) as total FROM users_cards WHERE userId = '$userID' AND `hidden` = '1'")[0]['total'] ?? 0;
?>

<div class="space-y-8">
    <!-- Profile Header -->
    <div class="flex flex-col items-center text-center">
        <div class="relative">
            <img src="https://ui-avatars.com/api/?name=<?php echo $user['firstName'] . '+' . $user['lastName'] ?>&background=FF9F43&color=fff&size=128" 
                 class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
            <button class="absolute bottom-0 right-0 w-10 h-10 bg-primary text-white rounded-full border-4 border-gray-50 dark:border-gray-900 flex items-center justify-center">
                <i class="fas fa-camera text-sm"></i>
            </button>
        </div>
        <h1 class="mt-4 text-2xl font-bold"><?php echo $user['firstName'] . ' ' . $user['lastName'] ?></h1>
        <p class="text-gray-500 text-sm"><?php echo $user['phone'] ?></p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-3xl font-bold text-primary"><?php echo $scansCount ?></p>
            <p class="text-xs text-gray-500 mt-1"><?php echo direction("Total Scans", "إجمالي المسحات") ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-3xl font-bold text-primary">12</p>
            <p class="text-xs text-gray-500 mt-1"><?php echo direction("Active Cards", "بطاقات نشطة") ?></p>
        </div>
    </div>

    <!-- Info List -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo direction("Email", "البريد الإلكتروني") ?></p>
                    <p class="font-medium"><?php echo $user['email'] ?></p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo direction("Address", "العنوان") ?></p>
                    <p class="font-medium">Kuwait City, Al-Asimah</p>
                </div>
            </div>
        </div>
        <a href="?v=EditProfile" class="block w-full py-4 bg-gray-50 dark:bg-gray-700/50 text-center text-sm font-bold text-primary hover:bg-gray-100 transition-all">
            <?php echo direction("Edit Profile", "تعديل الملف الشخصي") ?>
        </a>
    </div>

    <!-- QR Preview -->
    <a href="?v=QRCode" class="block bg-gradient-to-br from-primary to-orange-600 p-6 rounded-3xl shadow-lg text-white relative overflow-hidden group">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-1"><?php echo direction("My QR Code", "رمز QR الخاص بي") ?></h3>
                <p class="text-white/80 text-sm"><?php echo direction("Show this to the store", "أظهره للمتجر عند المسح") ?></p>
            </div>
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-qrcode text-3xl"></i>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute -top-10 -left-10 w-24 h-24 bg-white/10 rounded-full"></div>
    </a>

    <!-- Settings Link -->
    <a href="?v=Settings" class="flex items-center justify-between bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                <i class="fas fa-cog"></i>
            </div>
            <span class="font-bold"><?php echo direction("Settings", "الإعدادات") ?></span>
        </div>
        <i class="fas fa-chevron-right text-gray-300"></i>
    </a>
</div>
