<div class="content-panel" id="gallery">
    <div class="section-header">
        <h2 class="section-title"><?php echo direction("Photo Gallery","معرض الصور") ?></h2>
        <p class="section-subtitle"><?php echo direction("Capturing our beautiful moments","لحظاتنا الجميلة") ?></p>
    </div>
    
    <div class="gallery-grid">
        <?php 
        if(!empty($event["gallery"])){
            $galleryImages = json_decode($event["gallery"], true);
            if(is_array($galleryImages)){
                foreach($galleryImages as $index => $image){
                    if(!empty($image)){
                        echo '<div class="gallery-item" data-bs-toggle="modal" data-bs-target="#galleryModal" data-image="'.$image.'">';
                        echo '<img src="'.$image.'" alt="Gallery Image '.($index+1).'" loading="lazy">';
                        echo '</div>';
                    }
                }
            }
        }
        ?>
    </div>
</div>
