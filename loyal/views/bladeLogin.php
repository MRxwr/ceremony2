<div class="min-h-[80vh] flex flex-col justify-center px-4">
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-primary rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-primary/20">
            <i class="fas fa-crown text-white text-3xl"></i>
        </div>
        <h1 class="text-3xl font-bold"><?php echo direction("Welcome Back!", "مرحباً بعودتك!") ?></h1>
        <p class="text-gray-500 mt-2"><?php echo direction("Login to your loyalty account", "سجل دخولك لحساب الولاء الخاص بك") ?></p>
    </div>

    <form id="loginForm" class="space-y-6">
        <input type="hidden" name="endpoint" value="login">
        <div class="space-y-4">
            <div class="relative">
                <input type="tel" name="phone" required
                       placeholder="<?php echo direction("Mobile Number", "رقم الهاتف") ?>" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="relative">
                <input type="password" name="password" required
                       placeholder="<?php echo direction("Password", "كلمة المرور") ?>" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="?v=ForgotPassword" class="text-sm font-bold text-primary"><?php echo direction("Forgot Password?", "نسيت كلمة المرور؟") ?></a>
        </div>

        <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform active:scale-[0.98]">
            <?php echo direction("Login", "تسجيل الدخول") ?>
        </button>
    </form>

    <div class="mt-10 text-center">
        <p class="text-gray-500">
            <?php echo direction("Don't have an account?", "ليس لديك حساب؟") ?> 
            <a href="?v=Register" class="text-primary font-bold ml-1"><?php echo direction("Sign Up", "إنشاء حساب") ?></a>
        </p>
    </div>
</div>

<script>
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.post('requests/index.php?a=Users', formData, function(response) {
            if (response.ok) {
                window.location.href = 'index.php';
            } else {
                alert(response.data.msg || 'Login failed');
            }
        }, 'json');
    });
</script>
