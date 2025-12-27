<?php
// Fetch User's Cards
// Assuming $userID is available from session/config
$userID = 1; // Dummy for now
$sql = "SELECT uc.*, c.enTitle as cardEn, c.arTitle as cardAr, s.enTitle as storeEn, s.arTitle as storeAr, s.logo as storeImage 
        FROM users_cards uc 
        JOIN cards c ON uc.cardId = c.id 
        JOIN stores s ON c.storeId = s.id 
        WHERE uc.userId = '$userID' AND uc.status = '0' AND uc.hidden = '1'";
$userCards = queryDB($sql);
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold"><?php echo direction("My Wallet", "محفظتي") ?></h1>
        <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold">
            <?php echo count($userCards) ?> <?php echo direction("Cards", "بطاقات") ?>
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <?php if ($userCards): foreach ($userCards as $card): ?>
            <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
                <!-- Card Header -->
                <div class="flex items-center gap-4 mb-6">
                    <img src="storage/<?php echo $card['storeImage'] ?>" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                    <div>
                        <h3 class="font-bold text-lg"><?php echo urldecode(direction($card['storeEn'], $card['storeAr'])) ?></h3>
                        <p class="text-xs text-gray-500"><?php echo urldecode(direction($card['cardEn'], $card['cardAr'])) ?></p>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="space-y-4">
                    <?php if ($card['requriedStamps'] > 0): ?>
                        <!-- Stamps Progress -->
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span><?php echo direction("Stamps", "الأختام") ?></span>
                                <span class="text-primary"><?php echo $card['collectedStamps'] ?> / <?php echo $card['requriedStamps'] ?></span>
                            </div>
                            <div class="flex gap-2">
                                <?php for($i=1; $i<=$card['requriedStamps']; $i++): ?>
                                    <div class="flex-1 h-10 rounded-lg flex items-center justify-center border-2 <?php echo ($i <= $card['collectedStamps']) ? 'bg-primary border-primary text-white' : 'border-gray-100 dark:border-gray-700 text-gray-200' ?>">
                                        <i class="fas fa-stamp text-sm"></i>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Points Progress -->
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span><?php echo direction("Points", "النقاط") ?></span>
                                <span class="text-primary"><?php echo $card['collectedPoints'] ?> / <?php echo $card['requriedPoints'] ?></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 h-3 rounded-full overflow-hidden">
                                <div class="bg-primary h-full transition-all duration-1000" style="width: <?php echo ($card['collectedPoints'] / $card['requriedPoints']) * 100 ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Card Footer -->
                <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex justify-between items-center">
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider">
                        <?php echo direction("Last scan:", "آخر مسح:") ?> <?php echo date('d M Y', strtotime($card['date'])) ?>
                    </div>
                    <button class="text-primary font-bold text-sm flex items-center gap-2">
                        <?php echo direction("Details", "التفاصيل") ?>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="py-20 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
                <p class="text-gray-500"><?php echo direction("Your wallet is empty", "محفظتك فارغة") ?></p>
                <a href="?v=Explore" class="mt-4 inline-block text-primary font-bold"><?php echo direction("Explore Stores", "استكشف المتاجر") ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>
