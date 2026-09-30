<?php
/* Template Name: Expert Page */
get_header();
$id = get_the_ID();
$page = get_post($id);
?>

<section class="bg-dark-blue">
    <div class="container text-white no-pad-gutters">
        <h3 class="text-uppercase mb-4">ACTIVE OPERATIVE DATABASE</h3>
        <div class="row">
            <div class="col-md-8 mb-4">
              <p>Filter to find the exact asset required for your contract.</p>
              <h3 class="text-uppercase mb-4">FILTERS</h3>
            </div>
        </div>
        
        <!--May implement the search and filter here-->
        <div class="row">
            <div class="col team_filter-col">
                <div class="sector_filter filter_container">
                    <select id="industry-filter" class="form-select">
                    <option value="">All Industries</option>
                    <?php
                    $industries = get_posts([
                    'post_type'=>'industry',
                    'numberposts'=>-1
                    ]);
                    foreach($industries as $industry){
                    echo '<option value="'.$industry->ID.'">'
                    .$industry->post_title.
                    '</option>';
                    }
                    ?>
                    </select>
                </div>
                <div class="location_filter filter_container">
                    <select id="location-filter" class="form-select">
                    <option value="">All Locations</option>
                    <?php
                    $locations = get_posts([
                    'post_type'=>'location',
                    'numberposts'=>-1
                    ]);
                    foreach($locations as $location){
                    echo '<option value="'.$location->ID.'">'
                    .$location->post_title.
                    '</option>';
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 ml-auto">
                <div class="input-group mb-3">
                    <input type="text" class="form-control border border-light bg-transparent text-white rounded-0" id="expert-search" placeholder="Search">
                    <button class="btn btn-outline-light border border-light bg-transparent text-white rounded-0" id="basic-addon1" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                
                </div>
            </div>
        </div>
        <!-- search filter end here -->
    </div>
</section>

<!--May implement the experts profile list here-->
<div class="page-center">

    <div class="container">
    <div class="row" id="roster-results">

    <?php
        $args = array('post_type' => 'expert', 'posts_per_page' => -1);
        $experts = new WP_Query($args);
        if($experts->have_posts()):
        while($experts->have_posts()): $experts->the_post();
            $position = get_field('position');
            $profile_img = get_field('profile_image');
            $location = get_field('location');
            $email = get_field('email');
            $linkedin = get_field('linkedin');
        ?>
        <div class="col-md-4 text-center mb-4">
            <div class="team-box-inner">
                <div class="team-img">
                    <a href="<?php the_permalink(); ?>">
                        <img width="200" height="200" src="<?php echo $profile_img['url']; ?>" class="team-img-single" alt="<?php echo $profile_img['alt']; ?>" decoding="async">          
                    </a>
                </div>
                <div class="member-name">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
                <div class="member-desigantion"><?php echo $position; ?></div>
                <div class="member-address"><?php if ($location) {echo esc_html(get_the_title($location));}?></div>
                <div class="social-icons text-center">
                    <ul class="experts-socials p-0 justify-content-center align-items-center">
                        <li><a href="mailto:<?php echo $email; ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                        <li><a href="tel:"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
                        <li><a href="<?php echo $linkedin; ?>" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endwhile; endif; wp_reset_postdata(); ?>

        </div>
    </div>
</div>

<?php
get_footer();
?>