<div class="content-panel" id="about">
    <div class="section-header">
        <h2 class="section-title"><?php echo direction("Our Story","قصتنا") ?></h2>
        <p class="section-subtitle"><?php echo direction("How it all began","كيف بدأ كل شيء") ?></p>
    </div>
    
    <div class="story-photos">
        <?php if(!empty($event["groomPhoto"])): ?>
            <img src="<?php echo $event["groomPhoto"]; ?>" alt="Groom" class="person-photo">
        <?php endif; ?>
        <?php if(!empty($event["bridePhoto"])): ?>
            <img src="<?php echo $event["bridePhoto"]; ?>" alt="Bride" class="person-photo">
        <?php endif; ?>
    </div>
    
    <div class="decorative-divider"></div>
    
    <div class="love-story-text">
        <?php 
        if(!empty($event["story"])) {
            echo $event["story"];
        } else {
            echo direction(
                "Every love story is beautiful, but ours is our favorite. We're excited to start this new chapter together and would love to have you celebrate with us.",
                "كل قصة حب جميلة، لكن قصتنا هي المفضلة لدينا. نحن متحمسون لبدء هذا الفصل الجديد معاً ونود أن تحتفلوا معنا."
            );
        }
        ?>
    </div>
</div>
