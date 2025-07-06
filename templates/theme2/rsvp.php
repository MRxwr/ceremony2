<div class="content-panel" id="rsvp">
    <div class="section-header">
        <h2 class="section-title"><?php echo direction("RSVP","تأكيد الحضور") ?></h2>
        <p class="section-subtitle"><?php echo direction("Please confirm your attendance","يرجى تأكيد حضوركم") ?></p>
    </div>
    
    <form id="rsvpForm" action="requests/views/apiRsvp.php" method="POST">
        <input type="hidden" name="eventId" value="<?php echo $event['id']; ?>">
        <input type="hidden" name="inviteeId" value="<?php echo isset($_GET['i']) ? $_GET['i'] : ''; ?>">
        
        <div class="form-group">
            <input type="text" class="form-control" name="guestName" placeholder="<?php echo direction('Your Full Name','الاسم الكامل') ?>" required>
        </div>
        
        <div class="form-group">
            <input type="tel" class="form-control" name="guestPhone" placeholder="<?php echo direction('Phone Number','رقم الهاتف') ?>" required>
        </div>
        
        <div class="form-group">
            <select class="form-select" name="attendance" required>
                <option value=""><?php echo direction('Will you attend?','هل ستحضر؟') ?></option>
                <option value="yes"><?php echo direction('Yes, I will attend','نعم، سأحضر') ?></option>
                <option value="no"><?php echo direction('Sorry, I cannot attend','آسف، لا أستطيع الحضور') ?></option>
            </select>
        </div>
        
        <div class="form-group" id="guestCountGroup" style="display: none;">
            <select class="form-select" name="guestCount">
                <option value="1">1 <?php echo direction('person','شخص') ?></option>
                <option value="2">2 <?php echo direction('people','أشخاص') ?></option>
                <option value="3">3 <?php echo direction('people','أشخاص') ?></option>
                <option value="4">4 <?php echo direction('people','أشخاص') ?></option>
                <option value="5">5 <?php echo direction('people','أشخاص') ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <textarea class="form-control" name="specialRequests" rows="3" placeholder="<?php echo direction('Special requests or dietary requirements','طلبات خاصة أو متطلبات غذائية') ?>"></textarea>
        </div>
        
        <button type="submit" class="btn-submit">
            <?php echo direction('Confirm RSVP','تأكيد الحضور') ?>
        </button>
    </form>
</div>
