<?php
// Fetch Banners
$banners = selectDB("banners", "`status` = '0' AND `hidden` = '1' ORDER BY `rank` ASC");

// Fetch Leaderboard (Top 5 users by total points/stamps)
$leaderboard = selectDB("users_cards", "`status` = '0' AND `hidden` = '1' GROUP BY `userId` ORDER BY SUM(`collectedPoints` + `collectedStamps`) DESC LIMIT 5");
// Note: In a real scenario, we'd join with users table to get names.
?>

<div class="space-y-8">
    <!-- Banners Carousel -->
    <div class="relative overflow-hidden rounded-2xl bg-gray-200 h-48 md:h-64">
        <?php if ($banners): ?>
            <div class="flex transition-transform duration-500 ease-in-out h-full" id="banner-slider">
                <?php foreach ($banners as $banner): ?>
                    <div class="min-w-full h-full relative">
                        <img src="storage/<?php echo $banner['image'] ?>" alt="<?php echo urldecode($banner['title']) ?>" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent text-white">
                            <h3 class="font-bold text-lg"><?php echo urldecode(direction($banner['title'], $banner['title'])) ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex items-center justify-center h-full text-gray-400">
                <i class="fas fa-image text-4xl"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- My Scans Today -->
    <section>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold"><?php echo direction("My Scans Today", "مسحاتي اليوم") ?></h2>
            <span class="text-primary font-medium">3 <?php echo direction("Scans", "مسحات") ?></span>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center text-primary">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500"><?php echo direction("Points", "نقاط") ?></p>
                    <p class="font-bold">120</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-500">
                    <i class="fas fa-stamp"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500"><?php echo direction("Stamps", "أختام") ?></p>
                    <p class="font-bold">5</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leaderboard -->
    <section class="bg-primary/10 dark:bg-primary/5 p-6 rounded-2xl">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-trophy text-yellow-500"></i>
            <?php echo direction("Leaderboard", "لوحة المتصدرين") ?>
        </h2>
        <div class="space-y-3">
            <?php 
            // Dummy data for leaderboard if DB is empty
            $dummyLeaderboard = [
                ['name' => 'Ahmed Ali', 'scans' => 150],
                ['name' => 'Sara Smith', 'scans' => 135],
                ['name' => 'John Doe', 'scans' => 120],
            ];
            foreach ($dummyLeaderboard as $index => $user): 
            ?>
                <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-bold">
                            <?php echo $index + 1 ?>
                        </span>
                        <span class="font-medium"><?php echo $user['name'] ?></span>
                    </div>
                    <span class="text-primary font-bold"><?php echo $user['scans'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- News from Stores -->
    <section>
        <h2 class="text-xl font-bold mb-4"><?php echo direction("News from Stores", "أخبار المتاجر") ?></h2>
        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
            <?php for($i=1; $i<=3; $i++): ?>
                <div class="min-w-[280px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <img src="https://picsum.photos/seed/news<?php echo $i ?>/400/200" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold mb-1">Store Offer #<?php echo $i ?></h3>
                        <p class="text-sm text-gray-500 line-clamp-2">Get double points this weekend at all our branches!</p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- Top Rated & New Stores -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <section>
            <h2 class="text-xl font-bold mb-4"><?php echo direction("Top Rated", "الأعلى تقييماً") ?></h2>
            <div class="space-y-4">
                <?php for($i=1; $i<=3; $i++): ?>
                    <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <img src="https://picsum.photos/seed/store<?php echo $i ?>/100/100" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">Premium Coffee Shop</h4>
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300"></i>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4"><?php echo direction("New Stores", "متاجر جديدة") ?></h2>
            <div class="space-y-4">
                <?php for($i=4; $i<=6; $i++): ?>
                    <div class="flex items-center gap-4 bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <img src="https://picsum.photos/seed/store<?php echo $i ?>/100/100" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">New Fashion Hub</h4>
                            <p class="text-xs text-gray-500">Opened 2 days ago</p>
                        </div>
                        <span class="bg-green-100 text-green-600 text-[10px] px-2 py-1 rounded-full font-bold">NEW</span>
                    </div>
                <?php endfor; ?>
            </div>
        </section>
    </div>
</div>

<script>
    // Simple Banner Slider
    const slider = document.getElementById('banner-slider');
    if (slider) {
        let current = 0;
        const count = slider.children.length;
        setInterval(() => {
            current = (current + 1) % count;
            slider.style.transform = `translateX(-${current * 100}%)`;
        }, 5000);
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
