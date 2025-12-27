<?php
// Fetch Categories
$categories = selectDB("category", "`status` = '0' AND `hidden` = '1' ORDER BY `id` ASC");

// Fetch Stores (with search and category filter)
$where = "`status` = '0' AND `hidden` = '1'";
if (isset($_GET['cat'])) {
    $catId = (int)$_GET['cat'];
    $where .= " AND `categoryId` = '$catId'";
}
if (isset($_GET['q'])) {
    $q = escapeStringDirect($_GET['q']);
    $where .= " AND (`enTitle` LIKE '%$q%' OR `arTitle` LIKE '%$q%')";
}
$stores = selectDB("store", "$where ORDER BY `id` DESC");
?>

<div class="space-y-6">
    <!-- Search Bar -->
    <form action="" method="GET" class="relative">
        <input type="hidden" name="v" value="Explore">
        <input type="text" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : '' ?>" 
               placeholder="<?php echo direction("Search stores...", "ابحث عن المتاجر...") ?>" 
               class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </form>

    <!-- Categories -->
    <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
        <a href="?v=Explore" class="px-6 py-2 rounded-full whitespace-nowrap transition-all <?php echo !isset($_GET['cat']) ? 'bg-primary text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700' ?>">
            <?php echo direction("All", "الكل") ?>
        </a>
        <?php if ($categories): foreach ($categories as $cat): ?>
            <a href="?v=Explore&cat=<?php echo $cat['id'] ?>" class="px-6 py-2 rounded-full whitespace-nowrap transition-all <?php echo (isset($_GET['cat']) && $_GET['cat'] == $cat['id']) ? 'bg-primary text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700' ?>">
                <?php echo direction($cat['enTitle'], $cat['arTitle']) ?>
            </a>
        <?php endforeach; endif; ?>
    </div>

    <!-- Stores List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if ($stores): foreach ($stores as $store): ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden group hover:shadow-md transition-all">
                <div class="relative h-40 overflow-hidden">
                    <img src="logos/<?php echo $store['image'] ?>" alt="<?php echo $store['enTitle'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 px-2 py-1 rounded-lg text-[10px] font-bold text-primary">
                        <i class="fas fa-star mr-1"></i> 4.8
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1"><?php echo direction($store['enTitle'], $store['arTitle']) ?></h3>
                    <p class="text-sm text-gray-500 mb-4"><?php echo direction("Loyalty Program Active", "برنامج الولاء مفعّل") ?></p>
                    <a href="?v=StoreDetails&id=<?php echo $store['id'] ?>" class="block w-full py-3 bg-orange-50 dark:bg-orange-900/20 text-primary text-center rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all">
                        <?php echo direction("View Details", "عرض التفاصيل") ?>
                    </a>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-store-slash text-3xl"></i>
                </div>
                <p class="text-gray-500"><?php echo direction("No stores found", "لم يتم العثور على متاجر") ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
