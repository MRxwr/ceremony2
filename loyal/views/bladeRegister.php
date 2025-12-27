<div class="min-h-[80vh] flex flex-col justify-center px-4 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold"><?php echo direction("Create Account", "إنشاء حساب") ?></h1>
        <p class="text-gray-500 mt-2"><?php echo direction("Join our loyalty program today", "انضم لبرنامج الولاء الخاص بنا اليوم") ?></p>
    </div>

    <form id="registerForm" class="space-y-4">
        <input type="hidden" name="endpoint" value="register">
        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                <input type="text" name="firstName" required
                       placeholder="<?php echo direction("First Name", "الاسم الأول") ?>" 
                       class="w-full px-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            </div>
            <div class="relative">
                <input type="text" name="lastName" required
                       placeholder="<?php echo direction("Last Name", "الاسم الأخير") ?>" 
                       class="w-full px-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            </div>
        </div>

        <div class="relative">
            <input type="email" name="email" required
                   placeholder="<?php echo direction("Email Address", "البريد الإلكتروني") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

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

        <div class="relative">
            <input type="text" name="code" required
                   placeholder="<?php echo direction("Verification Code", "كود التحقق") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <div class="flex items-start gap-3 px-2 py-2">
            <input type="checkbox" required class="mt-1 accent-primary">
            <p class="text-xs text-gray-500">
                <?php echo direction("I agree to the", "أوافق على") ?> 
                <a href="?v=Terms" class="text-primary font-bold"><?php echo direction("Terms & Conditions", "الشروط والأحكام") ?></a> 
                <?php echo direction("and", "و") ?> 
                <a href="?v=Privacy" class="text-primary font-bold"><?php echo direction("Privacy Policy", "سياسة الخصوصية") ?></a>
            </p>
        </div>

        <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform active:scale-[0.98]">
            <?php echo direction("Create Account", "إنشاء الحساب") ?>
        </button>
    </form>

    <div class="mt-10 text-center">
        <p class="text-gray-500">
            <?php echo direction("Already have an account?", "لديك حساب بالفعل؟") ?> 
            <a href="?v=Login" class="text-primary font-bold ml-1"><?php echo direction("Login", "تسجيل الدخول") ?></a>
        </p>
    </div>
</div>

<script>
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = {};
        let firstName = '', lastName = '';
        formData.forEach(item => {
            if (item.name === 'firstName') firstName = item.value;
            else if (item.name === 'lastName') lastName = item.value;
            else data[item.name] = item.value;
        });
        data['fullName'] = firstName + ' ' + lastName;

        $.post('requests/index.php?a=Users', data, function(response) {
            if (response.ok) {
                alert('Account created successfully!');
                window.location.href = '?v=Login';
            } else {
                alert(response.data.msg || 'Registration failed');
            }
        }, 'json');
    });
</script>
