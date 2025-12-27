<?php
// Fetch Settings
$settings = selectDB("settings", "`id` = '1'")[0];
?>

<div class="space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <button onclick="window.history.back()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 text-gray-500">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h1 class="text-2xl font-bold"><?php echo direction("About Us", "عن التطبيق") ?></h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 prose dark:prose-invert max-w-none">
        <?php echo nl2br(urldecode($settings['about'])) ?>
    </div>
</div>
