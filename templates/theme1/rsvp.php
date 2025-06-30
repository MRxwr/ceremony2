<?php
if ( $invitee[0]["isConfirmed"] != 1 ){
?>
<div class="content-panel" id="rsvp-panel">
    <h3 class="text-center mb-3"><?php echo direction("RSVP","الدعوه") ?></h3>
    <div class="decorative-divider"></div>
    
    <form method="POST" id="rsvpForm">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="<?php echo direction("Full Name","الاسم الكامل") ?>" pattern="[A-Za-z\s]{3,}" <?php echo ( !empty($invitee[0]["name"]) ) ? "value='{$invitee[0]["name"]}' readonly" : "" ?> required>
        </div>
        <div class="form-group">
            <input type="tel" name="mobile" class="form-control" placeholder="<?php echo direction("Phone Number","رقم الهاتف") ?>" pattern="[0-9]{8,14}" <?php echo (!empty($invitee[0]["mobile"])) ? "value='{$invitee[0]["countryCode"]}{$invitee[0]["mobile"]}' readonly" : "" ?> required>
        </div>
        <div class="form-group">
            <select class="form-select" name="attendees" required>
                <option value="" selected disabled><?php echo direction("Number of Guests","عدد الحضور") ?></option>
                <?php 
                for ($i = 1; $i <= $invitee[0]["attendees"]; $i++) {
                    echo "<option value='{$i}'>{$i} " . direction("Guest" . ($i > 1 ? "s" : ""), ($i > 1 ? "ضيوف" : "ضيف")) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <select class="form-select" name="isConfirmed" required>
                <option value="" selected disabled ><?php echo direction("Will you attend?","سوف تحضر ؟") ?></option>
                <option value="1"><?php echo direction("Yes","نعم") ?></option>
                <option value="2"><?php echo direction("No","لا") ?></option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="<?php echo direction("Special message or dietary requirements (optional)","رسالة خاصة او طلبات غذائية") ?>" name="message"></textarea>
        </div>
        <div class="form-group">
            <?php echo "{$event["terms"]}"; ?>
        </div>
        <button type="submit" class="btn-submit"><?php echo direction("Send RSVP","ارسل الدعوه") ?></button>
    </form>
</div>
<?php
}else{
?>
<div class="content-panel" id="rsvp-panel">
    <h3 class="text-center mb-3"><?php echo direction("RSVP","الدعوه") ?></h3>
    <div class="decorative-divider"></div>

    <p class="text-center"><?php echo direction("Thank you for your RSVP! We look forward to celebrating with you.","شكرا لتأكيد حضورك! نتطلع للاحتفال معك.") ?></p>

    <p class="text-center"><?php echo direction("If you have any questions, please contact us.","إذا كان لديك أي استفسارات، يرجى الاتصال بنا.") ?></p>
    
    <button class="btn btn-primary" onclick="document.querySelector('[data-panel=\"home\"]').click();"><?php echo direction("Back to Home","العودة للصفحة الرئيسية") ?></button>
</div>
<?php
}
?>