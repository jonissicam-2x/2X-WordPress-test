<?php
/*
Template Post Type: expert
*/
get_header();
 
$teams_page = get_page_by_path('roster', OBJECT, 'page');
$teams_page_url = $teams_page
    ? get_permalink($teams_page->ID)
    : home_url('/roster/');
 
while (have_posts()):
    the_post();
 
    $id = get_the_ID();
 
    $position = get_field('position', $id);
    $email = get_field('email', $id);
    $contact = get_field('contact_no', $id);
    $linkedin = get_field('linkedin_url', $id);
    $location = get_field('location', $id);
    $profile_img = get_field('profile_image', $id);
    $expertises = get_field('expertise', $id);
 
    // Support no selection, one selection, or multiple selections.
    if (empty($expertises)) {
        $expertises = [];
    } elseif (!is_array($expertises)) {
        $expertises = [$expertises];
    }
 
    $industry_ids = [];
 
    foreach ($expertises as $expertise) {
        $industry_id = $expertise instanceof WP_Post
            ? $expertise->ID
            : (int) $expertise;
 
        if ($industry_id > 0) {
            $industry_ids[] = $industry_id;
        }
    }
 
    $industry_names = array_map('get_the_title', $industry_ids);
 
    // Support Image Array, Image ID, or Image URL.
    $profile_url = '';
    $profile_alt = get_the_title();
 
    if (is_array($profile_img)) {
        $profile_url = $profile_img['url'] ?? '';
        $profile_alt = !empty($profile_img['alt'])
            ? $profile_img['alt']
            : $profile_alt;
    } elseif (is_numeric($profile_img)) {
        $profile_url = wp_get_attachment_image_url(
            (int) $profile_img,
            'full'
        );
    } elseif (is_string($profile_img)) {
        $profile_url = $profile_img;
    }
?>
 
<section>
<div class="container no-pad-gutters">
<div class="back mb-4 mb-md-5">
<i
                class="fa fa-caret-left align-bottom"
                style="font-size: 22px;"
                aria-hidden="true"
></i>
 
            <a
                href="<?php echo esc_url($teams_page_url); ?>"
                class="btn-outline-success text-uppercase px-0 ml-2"
>
                Back to roster
</a>
</div>
 
        <div class="row my-5">
<div class="col-md-4">
<?php if ($profile_url): ?>
<img
                        src="<?php echo esc_url($profile_url); ?>"
                        class="img-fluid rounded mb-3"
                        alt="<?php echo esc_attr($profile_alt); ?>"
>
<?php endif; ?>
</div>
 
            <div class="col-md-8">

 <div class="profile-title">
                    <h2 class=""><?php the_title(); ?></h2>
                </div>
            

<div class="profile-designation">
                    <h6 class=""><em><?php echo esc_html($position ?: ''); ?></em></h6>
                </div>
 
            
 
<div class="city-title">
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;<?php echo esc_html(get_the_title($location)); ?></p>
                </div>

                
                <div class="social-icon">
                    <ul class="experts-socials">
                                                <li>
                            <a href="mailto:<?php
                            echo esc_url(
                                'mailto:' . sanitize_email($email)
                            );
                        ?>">
                                <i class="fa fa-envelope"></i>
                            </a>
                        </li>
                                                                                                <li>
                            <a href="<?php echo esc_url($linkedin); ?>" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </li>
                                            </ul>
                </div>
 
            
 
                <div class="mt-4">
<?php the_content(); ?>
</div>
</div>
</div>
</div>
</section>
 
<?php if ($industry_ids): ?>
<section class="text-bg-dark py-5 px-5">
<div class="container no-pad-gutters">
<h2 class="text-white text-center pb-5">
                Industry Expertise
</h2>
 
            <div class="row justify-content-center align-items-center">
<?php
                foreach ($industry_ids as $industry_id):
                    $expertise_name = get_the_title($industry_id);
                    $icon = get_field('image_icon', $industry_id);
                    $icon_id = 0;
                    $icon_url = '';
 
                    if (is_array($icon)) {
                        $icon_id = (int) ($icon['ID'] ?? 0);
                        $icon_url = $icon['url'] ?? '';
                    } elseif (is_numeric($icon)) {
                        $icon_id = (int) $icon;
                    } elseif (is_string($icon) && $icon !== '') {
                        $icon_url = $icon;
                        $icon_id = attachment_url_to_postid($icon);
                    }
                ?>
<div class="col industry_icon text-center">
<?php
                        if ($icon_id) {
                            echo wp_get_attachment_image(
                                $icon_id,
                                'full',
                                false,
                                [
                                    'loading' => 'lazy',
                                    'alt' => $expertise_name,
                                    'class' => 'img-fluid',
                                ]
                            );
                        } elseif ($icon_url) {
                            ?>
<img
                                src="<?php echo esc_url($icon_url); ?>"
                                alt="<?php echo esc_attr($expertise_name); ?>"
                                class="img-fluid"
                                loading="lazy"
>
<?php
                        }
                        ?>
 
                        
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>
 
<?php
endwhile;
 
get_footer();