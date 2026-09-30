<?php
$page = $template_args['page'];
// echo '<pre>'.print_r($page).'</pre>';
$feat_image = wp_get_attachment_url(get_post_thumbnail_id($page->ID)); 
?>
<div class="hero-inner has-parallax" style="padding-top:0;padding-bottom:0;aspect-ratio:3/2;height:unset;min-height:unset; background-position: 50% 50%; background-image: url('<?=$feat_image ?>');">
    <div class="gradient-layer"></div>

    <div class="wp-block-cover__inner-container has-global-padding is-layout-constrained wp-block-cover-is-layout-constrained">
    <p class="has-text-align-center wp-block-paragraph">Forged in the Shadows. Bound by the Contract.</p>
    </div>


    <div class="standout position-absolute">
        <img src="<?php echo wp_get_attachment_image_src($page->hero_section_foreground_image, 'full')[0]?>" class="img-fluid">
    </div>
    
</div>