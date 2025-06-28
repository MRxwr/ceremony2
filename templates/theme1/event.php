<div class="content-panel" id="event-panel">
    <h3 class="text-center mb-3">Wedding Details</h3>
    <div class="decorative-divider"></div>
    
    <div class="event-info">
        <h4><i class="bi bi-calendar-heart"></i> When</h4>
        <p class="mb-1"><strong>Date:</strong> {{EventDate}}</p>
        <p><strong>Time:</strong> {{EventTime}}</p>
    </div>
    
    <div class="event-info">
        <h4><i class="bi bi-geo-alt"></i> Where</h4>
        <p class="mb-1"><strong>{{VenueName}}</strong></p>
        <p>{{VenueAddress}}</p>
    </div>
    
    <div class="map-placeholder">
        {{MapEmbed}}
    </div>
</div>