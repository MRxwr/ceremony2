<div class="space-y-6">
    <h1 class="text-2xl font-bold mb-6"><?php echo direction("Settings", "الإعدادات") ?></h1>

    <!-- Appearance Section -->
    <section class="space-y-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4"><?php echo direction("Appearance", "المظهر") ?></h2>
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-500">
                        <i class="fas fa-moon"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Dark Mode", "الوضع الليلي") ?></span>
                </div>
                <button onclick="toggleDarkMode()" class="w-12 h-6 bg-gray-200 dark:bg-primary rounded-full relative transition-colors">
                    <div class="absolute top-1 left-1 dark:left-7 w-4 h-4 bg-white rounded-full transition-all"></div>
                </button>
            </div>
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-globe"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Language", "اللغة") ?></span>
                </div>
                <div class="flex gap-2">
                    <a href="?lang=ENG" class="px-3 py-1 rounded-lg text-xs font-bold <?php echo ($_COOKIE['CREATEkwLANG'] != 'AR') ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700' ?>">EN</a>
                    <a href="?lang=AR" class="px-3 py-1 rounded-lg text-xs font-bold <?php echo ($_COOKIE['CREATEkwLANG'] == 'AR') ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700' ?>">AR</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Section -->
    <section class="space-y-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4"><?php echo direction("Support & Legal", "الدعم والقانونية") ?></h2>
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <a href="?v=About" class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("About Us", "عن التطبيق") ?></span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </a>
            <a href="?v=Contact" class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-headset"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Contact Us", "اتصل بنا") ?></span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </a>
            <a href="?v=Complain" class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Complain", "تقديم شكوى") ?></span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </a>
            <a href="?v=Privacy" class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Privacy Policy", "سياسة الخصوصية") ?></span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </a>
            <a href="?v=Terms" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <span class="font-medium"><?php echo direction("Terms & Conditions", "الشروط والأحكام") ?></span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </a>
        </div>
    </section>

    <!-- Account Section -->
    <section class="space-y-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4"><?php echo direction("Account", "الحساب") ?></h2>
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <a href="?v=Logout" class="flex items-center justify-between p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-red-50 dark:bg-red-900/30 rounded-xl flex items-center justify-center text-red-500">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span class="font-medium text-red-500"><?php echo direction("Log Out", "تسجيل الخروج") ?></span>
                </div>
            </a>
            <button onclick="confirmDelete()" class="w-full flex items-center justify-between p-4 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-red-50 dark:bg-red-900/30 rounded-xl flex items-center justify-center text-red-500">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <span class="font-medium text-red-500"><?php echo direction("Delete Account", "حذف الحساب") ?></span>
                </div>
            </button>
        </div>
    </section>

    <div class="text-center py-8">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Version 2.1.0</p>
        <p class="text-[10px] text-gray-300 mt-1">© 2025 Loyal Ceremony System</p>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('<?php echo direction("Are you sure you want to delete your account? This action cannot be undone.", "هل أنت متأكد من حذف حسابك؟ لا يمكن التراجع عن هذا الإجراء.") ?>')) {
            window.location.href = '?v=DeleteAccount';
        }
    }
</script>
