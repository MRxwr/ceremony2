<div class="content-panel active" id="home">
    <div class="section-header">
        <h2 class="section-title"><?php echo direction("Welcome","أهلاً وسهلاً") ?></h2>
        <p class="section-subtitle"><?php echo direction("We're excited to celebrate with you","نتطلع للاحتفال معكم") ?></p>
    </div>
    
    <div class="decorative-divider"></div>
    
    <div class="love-story-text">
        <?php echo direction(
            "Join us as we celebrate the beginning of our forever journey together. Your presence would make our special day even more meaningful.",
            "انضموا إلينا للاحتفال ببداية رحلتنا الأبدية معاً. حضوركم سيجعل يومنا الخاص أكثر معنى وجمالاً."
        ); ?>
    </div>
    
    <div class="countdown">
        <div class="countdown-item">
            <div class="countdown-value" id="days">00</div>
            <div class="countdown-label"><?php echo direction("Days","أيام") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="hours">00</div>
            <div class="countdown-label"><?php echo direction("Hours","ساعات") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="minutes">00</div>
            <div class="countdown-label"><?php echo direction("Minutes","دقائق") ?></div>
        </div>
        <div class="countdown-item">
            <div class="countdown-value" id="seconds">00</div>
            <div class="countdown-label"><?php echo direction("Seconds","ثواني") ?></div>
        </div>
    </div>
</div>
