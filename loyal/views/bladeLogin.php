<div class="min-h-[80vh] flex flex-col justify-center px-4 py-10">
    <!-- Header -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-primary rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-primary/20">
            <i class="fas fa-crown text-white text-3xl"></i>
        </div>
        <h1 id="authTitle" class="text-3xl font-bold"><?php echo direction("Welcome!", "????? ??!") ?></h1>
        <p id="authSubtitle" class="text-gray-500 mt-2"><?php echo direction("Enter your phone to continue", "???? ??? ????? ????????") ?></p>
    </div>

    <!-- Step 1: Phone Number -->
    <div id="stepPhone" class="space-y-6">
        <div class="relative">
            <input type="tel" id="phoneInput" 
                   placeholder="<?php echo direction("Mobile Number (e.g. +965...)", "??? ?????? (????: +965...)") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button onclick="sendCode()" id="btnSendCode" class="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform active:scale-[0.98]">
            <?php echo direction("Send Verification Code", "????? ??? ??????") ?>
        </button>
    </div>

    <!-- Step 2: Verification Code -->
    <div id="stepCode" class="hidden space-y-6">
        <div class="relative">
            <input type="text" id="codeInput" 
                   placeholder="<?php echo direction("Verification Code", "??? ??????") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-center tracking-[1em] font-bold">
            <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button onclick="verifyCode()" id="btnVerifyCode" class="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform active:scale-[0.98]">
            <?php echo direction("Verify & Continue", "???? ??????") ?>
        </button>
        <button onclick="backToPhone()" class="w-full text-gray-500 font-medium text-sm">
            <?php echo direction("Change Phone Number", "????? ??? ??????") ?>
        </button>
    </div>

    <!-- Step 3: Profile Completion -->
    <div id="stepProfile" class="hidden space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                <input type="text" id="firstName" 
                       placeholder="<?php echo direction("First Name", "????? ?????") ?>" 
                       class="w-full px-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            </div>
            <div class="relative">
                <input type="text" id="lastName" 
                       placeholder="<?php echo direction("Last Name", "????? ??????") ?>" 
                       class="w-full px-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            </div>
        </div>
        <div class="relative">
            <input type="email" id="email" 
                   placeholder="<?php echo direction("Email (Optional)", "?????? ?????????? (???????)") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <div class="relative">
            <input type="text" id="address" 
                   placeholder="<?php echo direction("Address (Optional)", "??????? (???????)") ?>" 
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
            <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button onclick="updateProfile()" id="btnUpdateProfile" class="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform active:scale-[0.98]">
            <?php echo direction("Complete Registration", "????? ???????") ?>
        </button>
    </div>

    <!-- Footer Links -->
    <div id="authFooter" class="mt-10 text-center">
        <p class="text-xs text-gray-400 px-6">
            <?php echo direction("By continuing, you agree to our", "????????? ??? ????? ???") ?> 
            <a href="?v=Terms" class="text-primary underline"><?php echo direction("Terms", "??????") ?></a> 
            <?php echo direction("and", "?") ?> 
            <a href="?v=Privacy" class="text-primary underline"><?php echo direction("Privacy Policy", "????? ????????") ?></a>
        </p>
    </div>
</div>

<script>
let userToken = '';

function sendCode() {
    const phone = $('#phoneInput').val();
    if (!phone) return alert('<?php echo direction("Please enter phone number", "???? ????? ??? ??????") ?>');
    
    $('#btnSendCode').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.post('requests/index.php?a=Users', { endpoint: 'sendCode', phone: phone }, function(response) {
        if (response.ok) {
            $('#stepPhone').addClass('hidden');
            $('#stepCode').removeClass('hidden');
            $('#authSubtitle').text('<?php echo direction("Enter the code sent to ", "???? ????? ?????? ??? ") ?>' + phone);
        } else {
            alert(response.data.msg || 'Error sending code');
        }
        $('#btnSendCode').prop('disabled', false).text('<?php echo direction("Send Verification Code", "????? ??? ??????") ?>');
    }, 'json');
}

function verifyCode() {
    const phone = $('#phoneInput').val();
    const code = $('#codeInput').val();
    if (!code) return alert('<?php echo direction("Please enter code", "???? ????? ?????") ?>');
    
    $('#btnVerifyCode').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.post('requests/index.php?a=Users', { endpoint: 'verifyCode', phone: phone, code: code }, function(response) {
        if (response.ok) {
            userToken = response.data.token;
            // Save token to cookie/localStorage
            document.cookie = "keepMeAlive=" + userToken + "; path=/; max-age=" + (365 * 24 * 60 * 60);
            
            if (response.data.profileComplete) {
                window.location.href = 'index.php';
            } else {
                $('#stepCode').addClass('hidden');
                $('#stepProfile').removeClass('hidden');
                $('#authTitle').text('<?php echo direction("One Last Step!", "???? ?????!") ?>');
                $('#authSubtitle').text('<?php echo direction("Tell us a bit about yourself", "?????? ????? ?? ????") ?>');
            }
        } else {
            alert(response.data.msg || 'Invalid code');
        }
        $('#btnVerifyCode').prop('disabled', false).text('<?php echo direction("Verify & Continue", "???? ??????") ?>');
    }, 'json');
}

function updateProfile() {
    const firstName = $('#firstName').val();
    const lastName = $('#lastName').val();
    const email = $('#email').val();
    const address = $('#address').val();
    
    if (!firstName || !lastName) return alert('<?php echo direction("First and Last name are required", "????? ????? ??????? ???????") ?>');
    
    $('#btnUpdateProfile').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: 'requests/index.php?a=Users',
        type: 'POST',
        headers: { 'Authorization': 'Bearer ' + userToken },
        data: { 
            endpoint: 'updateProfile', 
            firstName: firstName, 
            lastName: lastName, 
            email: email, 
            address: address 
        },
        success: function(response) {
            if (response.ok) {
                window.location.href = 'index.php';
            } else {
                alert(response.data.msg || 'Update failed');
            }
            $('#btnUpdateProfile').prop('disabled', false).text('<?php echo direction("Complete Registration", "????? ???????") ?>');
        },
        dataType: 'json'
    });
}

function backToPhone() {
    $('#stepCode').addClass('hidden');
    $('#stepPhone').removeClass('hidden');
    $('#authSubtitle').text('<?php echo direction("Enter your phone to continue", "???? ??? ????? ????????") ?>');
}
</script>
