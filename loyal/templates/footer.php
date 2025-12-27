    </main>

    <!-- Mobile Bottom Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 glass border-t border-gray-200 dark:border-gray-700 z-50 flex items-center justify-around px-2">
        <a href="index.php" class="flex flex-col items-center gap-1 <?php echo (!isset($_GET['v']) || $_GET['v'] == 'Home') ? 'text-primary' : 'text-gray-500 dark:text-gray-400' ?>">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[10px]"><?php echo direction("Home", "الرئيسية") ?></span>
        </a>
        <a href="?v=Explore" class="flex flex-col items-center gap-1 <?php echo (isset($_GET['v']) && $_GET['v'] == 'Explore') ? 'text-primary' : 'text-gray-500 dark:text-gray-400' ?>">
            <i class="fas fa-search text-lg"></i>
            <span class="text-[10px]"><?php echo direction("Explore", "استكشف") ?></span>
        </a>
        
        <!-- QR Button -->
        <div class="relative -top-6">
            <a href="?v=QRCode" class="flex items-center justify-center w-14 h-14 bg-primary text-white rounded-full shadow-lg border-4 border-gray-50 dark:border-gray-900">
                <i class="fas fa-qrcode text-2xl"></i>
            </a>
        </div>

        <a href="?v=Wallet" class="flex flex-col items-center gap-1 <?php echo (isset($_GET['v']) && $_GET['v'] == 'Wallet') ? 'text-primary' : 'text-gray-500 dark:text-gray-400' ?>">
            <i class="fas fa-wallet text-lg"></i>
            <span class="text-[10px]"><?php echo direction("Wallet", "المحفظة") ?></span>
        </a>
        <a href="?v=Profile" class="flex flex-col items-center gap-1 <?php echo (isset($_GET['v']) && $_GET['v'] == 'Profile') ? 'text-primary' : 'text-gray-500 dark:text-gray-400' ?>">
            <i class="fas fa-user text-lg"></i>
            <span class="text-[10px]"><?php echo direction("Profile", "حسابي") ?></span>
        </a>
    </nav>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Add any global JS here
    </script>
</body>
</html>
