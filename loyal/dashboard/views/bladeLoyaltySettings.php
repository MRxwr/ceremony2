<?php 
// Handle settings update
if( isset($_POST["updateSettings"]) ){
	foreach ($_POST as $key => $value) {
		if ($key !== 'updateSettings') {
			// Check if setting exists
			$existing = selectDB("loyalty_settings", "`settingKey` = '{$key}' LIMIT 1");
			if ($existing && is_array($existing) && count($existing) > 0) {
				// Update existing
				updateDB("loyalty_settings", array('settingValue' => $value), "`settingKey` = '{$key}'");
			} else {
				// Insert new
				insertDB("loyalty_settings", array(
					'settingKey' => $key,
					'settingValue' => $value,
					'date' => date('Y-m-d H:i:s'),
					'status' => '0',
					'hidden' => '1'
				));
			}
		}
	}
	?>
	<script>
		alert("<?php echo direction("Settings updated successfully","تم تحديث الإعدادات بنجاح") ?>");
	</script>
	<?php
}

// Load current settings
function getSetting($key, $default = '') {
	$result = selectDB("loyalty_settings", "`settingKey` = '{$key}' LIMIT 1");
	if ($result && is_array($result) && count($result) > 0) {
		return $result[0]['settingValue'];
	}
	return $default;
}
?>
<div class="row">
<div class="col-sm-12">
<div class="panel panel-default card-view">
<div class="panel-heading">
<div class="pull-left">
	<h6 class="panel-title txt-dark"><?php echo direction("Loyalty Platform Settings","إعدادات منصة الولاء") ?></h6>
</div>
	<div class="clearfix"></div>
</div>
<div class="panel-wrapper collapse in">
<div class="panel-body">
	<form class="" method="POST" action="">
		
		<!-- General Settings -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("General Settings","الإعدادات العامة") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label><?php echo direction("Platform Name (English)","اسم المنصة (إنجليزي)") ?></label>
						<input type="text" name="platform_name_en" class="form-control" value="<?php echo getSetting('platform_name_en', 'Loyalty Platform') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Platform Name (Arabic)","اسم المنصة (عربي)") ?></label>
						<input type="text" name="platform_name_ar" class="form-control" value="<?php echo getSetting('platform_name_ar', 'منصة الولاء') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Support Email","البريد الإلكتروني للدعم") ?></label>
						<input type="email" name="support_email" class="form-control" value="<?php echo getSetting('support_email', 'support@loyalty.com') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Support Phone","هاتف الدعم") ?></label>
						<input type="text" name="support_phone" class="form-control" value="<?php echo getSetting('support_phone', '') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Default Language","اللغة الافتراضية") ?></label>
						<select name="default_language" class="form-control">
							<option value="en" <?php echo getSetting('default_language') == 'en' ? 'selected' : '' ?>>English</option>
							<option value="ar" <?php echo getSetting('default_language') == 'ar' ? 'selected' : '' ?>>العربية</option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Currency","العملة") ?></label>
						<input type="text" name="currency" class="form-control" value="<?php echo getSetting('currency', 'SAR') ?>">
					</div>
				</div>
			</div>
		</div>
		
		<!-- Points & Rewards Settings -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("Points & Rewards Settings","إعدادات النقاط والمكافآت") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<label><?php echo direction("Default Points Per SAR","النقاط الافتراضية لكل ريال") ?></label>
						<input type="number" name="default_points_per_sar" class="form-control" value="<?php echo getSetting('default_points_per_sar', '1') ?>" step="0.1">
					</div>
					
					<div class="col-md-4">
						<label><?php echo direction("Points Expiry (Days)","انتهاء صلاحية النقاط (أيام)") ?></label>
						<input type="number" name="points_expiry_days" class="form-control" value="<?php echo getSetting('points_expiry_days', '365') ?>">
					</div>
					
					<div class="col-md-4">
						<label><?php echo direction("Min Points for Redemption","الحد الأدنى للنقاط للاسترداد") ?></label>
						<input type="number" name="min_redemption_points" class="form-control" value="<?php echo getSetting('min_redemption_points', '100') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Enable Points Expiry","تفعيل انتهاء صلاحية النقاط") ?></label>
						<select name="enable_points_expiry" class="form-control">
							<option value="1" <?php echo getSetting('enable_points_expiry', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_points_expiry', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Enable Referral Program","تفعيل برنامج الإحالة") ?></label>
						<select name="enable_referral" class="form-control">
							<option value="1" <?php echo getSetting('enable_referral', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_referral', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Referral Bonus Points","نقاط مكافأة الإحالة") ?></label>
						<input type="number" name="referral_bonus_points" class="form-control" value="<?php echo getSetting('referral_bonus_points', '50') ?>">
					</div>
				</div>
			</div>
		</div>
		
		<!-- Store Management Settings -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("Store Management","إدارة المتاجر") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label><?php echo direction("Require Store Approval","تتطلب الموافقة على المتجر") ?></label>
						<select name="require_store_approval" class="form-control">
							<option value="1" <?php echo getSetting('require_store_approval', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('require_store_approval', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Max Loyalty Programs Per Store","الحد الأقصى لبرامج الولاء لكل متجر") ?></label>
						<input type="number" name="max_programs_per_store" class="form-control" value="<?php echo getSetting('max_programs_per_store', '5') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Max Staff Per Store","الحد الأقصى للموظفين لكل متجر") ?></label>
						<input type="number" name="max_staff_per_store" class="form-control" value="<?php echo getSetting('max_staff_per_store', '10') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Commission Rate (%)","معدل العمولة (٪)") ?></label>
						<input type="number" name="platform_commission_rate" class="form-control" value="<?php echo getSetting('platform_commission_rate', '0') ?>" step="0.1">
					</div>
				</div>
			</div>
		</div>
		
		<!-- Notifications Settings -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("Notifications","الإشعارات") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<label><?php echo direction("Enable Email Notifications","تفعيل إشعارات البريد الإلكتروني") ?></label>
						<select name="enable_email_notifications" class="form-control">
							<option value="1" <?php echo getSetting('enable_email_notifications', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_email_notifications', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-4">
						<label><?php echo direction("Enable SMS Notifications","تفعيل إشعارات الرسائل القصيرة") ?></label>
						<select name="enable_sms_notifications" class="form-control">
							<option value="1" <?php echo getSetting('enable_sms_notifications', '0') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_sms_notifications', '0') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-4">
						<label><?php echo direction("Enable Push Notifications","تفعيل إشعارات الدفع") ?></label>
						<select name="enable_push_notifications" class="form-control">
							<option value="1" <?php echo getSetting('enable_push_notifications', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_push_notifications', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Notify on Points Earned","إشعار عند كسب النقاط") ?></label>
						<select name="notify_points_earned" class="form-control">
							<option value="1" <?php echo getSetting('notify_points_earned', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('notify_points_earned', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Notify on Reward Redemption","إشعار عند استرداد المكافأة") ?></label>
						<select name="notify_reward_redeemed" class="form-control">
							<option value="1" <?php echo getSetting('notify_reward_redeemed', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('notify_reward_redeemed', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Notify Before Points Expiry (Days)","إشعار قبل انتهاء النقاط (أيام)") ?></label>
						<input type="number" name="expiry_notification_days" class="form-control" value="<?php echo getSetting('expiry_notification_days', '30') ?>">
					</div>
				</div>
			</div>
		</div>
		
		<!-- Security Settings -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("Security","الأمان") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label><?php echo direction("Enable QR Code Encryption","تفعيل تشفير رمز الاستجابة السريعة") ?></label>
						<select name="enable_qr_encryption" class="form-control">
							<option value="1" <?php echo getSetting('enable_qr_encryption', '1') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('enable_qr_encryption', '1') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("QR Code Expiry (Minutes)","انتهاء صلاحية رمز الاستجابة السريعة (دقائق)") ?></label>
						<input type="number" name="qr_expiry_minutes" class="form-control" value="<?php echo getSetting('qr_expiry_minutes', '5') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Max Login Attempts","الحد الأقصى لمحاولات تسجيل الدخول") ?></label>
						<input type="number" name="max_login_attempts" class="form-control" value="<?php echo getSetting('max_login_attempts', '5') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Account Lockout Duration (Minutes)","مدة قفل الحساب (دقائق)") ?></label>
						<input type="number" name="lockout_duration_minutes" class="form-control" value="<?php echo getSetting('lockout_duration_minutes', '30') ?>">
					</div>
					
					<div class="col-md-6">
						<label><?php echo direction("Require Two-Factor Authentication","تتطلب المصادقة الثنائية") ?></label>
						<select name="require_2fa" class="form-control">
							<option value="1" <?php echo getSetting('require_2fa', '0') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('require_2fa', '0') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Maintenance Mode -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><?php echo direction("Maintenance Mode","وضع الصيانة") ?></h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label><?php echo direction("Enable Maintenance Mode","تفعيل وضع الصيانة") ?></label>
						<select name="maintenance_mode" class="form-control">
							<option value="1" <?php echo getSetting('maintenance_mode', '0') == '1' ? 'selected' : '' ?>><?php echo direction("Yes","نعم") ?></option>
							<option value="0" <?php echo getSetting('maintenance_mode', '0') == '0' ? 'selected' : '' ?>><?php echo direction("No","لا") ?></option>
						</select>
					</div>
					
					<div class="col-md-12">
						<label><?php echo direction("Maintenance Message (English)","رسالة الصيانة (إنجليزي)") ?></label>
						<textarea name="maintenance_message_en" class="form-control" rows="3"><?php echo getSetting('maintenance_message_en', 'System is under maintenance. Please check back later.') ?></textarea>
					</div>
					
					<div class="col-md-12">
						<label><?php echo direction("Maintenance Message (Arabic)","رسالة الصيانة (عربي)") ?></label>
						<textarea name="maintenance_message_ar" class="form-control" rows="3"><?php echo getSetting('maintenance_message_ar', 'النظام قيد الصيانة. يرجى المحاولة لاحقا.') ?></textarea>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<input type="submit" name="updateSettings" class="btn btn-primary btn-lg" value="<?php echo direction("Save All Settings","حفظ جميع الإعدادات") ?>">
			</div>
		</div>
	</form>
</div>
</div>
</div>
</div>
</div>
