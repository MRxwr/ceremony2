<div class="content-panel" id="rsvp-panel">
    <h3 class="text-center mb-3">RSVP</h3>
    <div class="decorative-divider"></div>
    
    <form action="" method="POST" id="rsvpForm">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Full Name" name="fullName" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Email Address" name="email" required>
        </div>
        <div class="form-group">
            <select class="form-select" name="guests" required>
                <option value="">Number of Guests</option>
                <option value="1">1 Guest</option>
                <option value="2">2 Guests</option>
                <option value="3">3 Guests</option>
                <option value="4">4 Guests</option>
                <option value="5+">5+ Guests</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-select" name="attendance" required>
                <option value="">Will you attend?</option>
                <option value="yes">Joyfully Accept</option>
                <option value="no">Regretfully Decline</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Special message or dietary requirements (optional)" name="message"></textarea>
        </div>
        <button type="submit" class="btn-submit">Send RSVP</button>
    </form>
</div>