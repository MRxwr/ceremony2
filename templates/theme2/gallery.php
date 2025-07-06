<?php
if( !empty($event["gallery"]) ){
    ?>
<div class="content-panel" id="gallery-panel">
    <h3 class="panel-title"><?php echo direction("Our Memories","ذكرياتنا") ?></h3>
    <p class="panel-subtitle"><?php echo direction("Moments we've shared together","اللحظات التي تشاركناها معاً") ?></p>
    
    <div class="gallery-grid">
        <?php 
            $galleryImages = json_decode($event["gallery"], true);
            if( is_array($galleryImages) && count($galleryImages) > 0 ){
                foreach($galleryImages as $index => $image){
                    if(!empty($image)){
                        echo '<div class="gallery-item">';
                        echo '<img src="logos/' . htmlspecialchars($image) . '" alt="Gallery ' . ($index + 1) . '">';
                        echo '</div>';
                    }
                }
            }
        ?>
    </div>
</div>
<?php
}
?>
