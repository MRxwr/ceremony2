<div class="card-footer-section">
    <div class="social-links">
        <?php if(!empty($event["socialMedia"])): ?>
            <?php
            $socialMedia = json_decode($event["socialMedia"], true);
            if(is_array($socialMedia)){
                foreach($socialMedia as $platform => $url){
                    if(!empty($url)){
                        $icon = '';
                        switch($platform){
                            case 'instagram': $icon = 'bi-instagram'; break;
                            case 'facebook': $icon = 'bi-facebook'; break;
                            case 'twitter': $icon = 'bi-twitter'; break;
                            case 'whatsapp': $icon = 'bi-whatsapp'; break;
                            case 'snapchat': $icon = 'bi-snapchat'; break;
                            default: $icon = 'bi-link-45deg'; break;
                        }
                        echo '<a href="'.$url.'" target="_blank" rel="noopener"><i class="bi '.$icon.'"></i></a>';
                    }
                }
            }
        ?>
        <?php else: ?>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-whatsapp"></i></a>
        <?php endif; ?>
    </div>
    <p style="margin: 0; color: var(--text-light); font-size: 0.85rem;">
        <?php echo direction("Made with","صُنع بـ") ?> <i class="bi bi-heart-fill" style="color: var(--light-blue);"></i> <?php echo direction("for your special day","ليومكم الخاص") ?>
    </p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include "script.php"; ?>

</body>
</html>
