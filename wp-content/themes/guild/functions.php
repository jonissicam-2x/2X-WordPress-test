<?php
require get_template_directory() . '/inc/bootstrap-navwalker.php';
require get_template_directory() . '/inc/custom-widget.php';
//require get_template_directory() . '/inc/custom-post-type.php';

add_theme_support('menus');
add_theme_support('title-tag');

// added func
add_filter('home_template', 'my_custom_posts_page_template');
function my_custom_posts_page_template($template) {
    $custom_template = get_stylesheet_directory() . '/templates/expert-page.php';
    
    if (file_exists($custom_template)) {
        return $custom_template;
    }
    
    return $template;
}

function roster_scripts() {

wp_enqueue_script(
'expert-filter',
get_template_directory_uri() . '/src/js/expert-filter.js',
['jquery'],
null,
true
);

wp_localize_script(
'expert-filter',
'expertAjax',
[
'ajaxurl' => admin_url('admin-ajax.php')
]
);

}
add_action('wp_enqueue_scripts', 'roster_scripts');

add_action(
'wp_ajax_filter_experts',
'filter_experts'
);

add_action(
'wp_ajax_nopriv_filter_experts',
'filter_experts'
);

function filter_experts() {

    $search   = sanitize_text_field($_POST['search'] ?? '');
    $industry = sanitize_text_field($_POST['industry'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');

    $meta_query = [];

    if (!empty($industry)) {

        $meta_query[] = [
            'key'     => 'expertise',
            'value'   => '"' . $industry . '"',
            'compare' => 'LIKE'
        ];
    }

  if (!empty($location)) {
    $meta_query[] = [
        'key'     => 'location',
        'value'   => $location,
        'compare' => '='
    ];
}

    if (count($meta_query) > 1) {
        $meta_query['relation'] = 'AND';
    }

    $args = [
        'post_type'      => 'expert',
        'posts_per_page' => -1,
        's'              => $search,
    ];

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) {

            $query->the_post();

            $position    = get_field('position');
            $profile_img = get_field('profile_image');
            $email       = get_field('email');
            $linkedin    = get_field('linkedin_url');
            $location    = get_field('location');

            ?>

            <div class="col-md-4 text-center mb-4">
                <div class="team-box-inner">

                    <div class="team-img">
                        <a href="<?php echo esc_html(get_the_title($location)); ?>">
                        <img
                                width="200"
                                src="<?php echo esc_attr($profile_img['url']); ?>"
                                alt="<?php echo esc_attr($profile_img['alt']); ?>"
                                decoding="async"
                            >

                         </a>
                    </div>

                    <div class="member-name">
                         
                            <?php the_title(); ?>
                       
                    </div>

                    <div class="member-desigantion">
                        <?php echo $position; ?>
                    </div>

                    <div class="member-address">
                        <?php
                        if ($location) {
                            echo esc_html($location->post_title);
                        }
                        ?>
                    </div>

                    <div class="social-icons text-center">
                        <ul class="experts-socials p-0 justify-content-center align-items-center">

                            <?php if ($email) : ?>
                                <li>
                                        <i class="fa fa-envelope"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($linkedin) : ?>
                                <li>
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>

                </div>
            </div>

            <?php
        }

    } else {

        echo '<div class="col-12 text-center">';
        echo '<p>No experts found.</p>';
        echo '</div>';

    }

    wp_reset_postdata();
    wp_die();
}
// end func


function enable_gutenberg_only_on_templates($use_block_editor, $post) {
    if (empty($post->ID)) {
        return $use_block_editor;
    }

    $template = get_page_template_slug($post->ID);

    // List the templates where Gutenberg MUST be enabled
    $enabled_templates = array(
        'templates/gutenberg.php'
    );

    if (in_array($template, $enabled_templates)) {
        return true; // Force Gutenberg on
    }

    return $use_block_editor;
}


// Note: Priority is set to 10 to override global disable rules set at priority 5
add_filter('use_block_editor_for_post', 'enable_gutenberg_only_on_templates', 10, 2);
function register_my_menu()
{
    register_nav_menus(
        array(
            'top' => __('Top menu'),
            'top_submenu' => __('Above Top menu'),
            'footer' => __('Footer menu'),
            'below_footer' => __('Below Footer menu'),
        )
    );
}

add_action('init', 'register_my_menu');

function custom_theme_assets()
{
    $manifest_file = get_template_directory() . '/manifest.json';
    if(file_exists($manifest_file)){
        
        // Read the manifest file to get the correct asset paths
        $manifest = json_decode(file_get_contents($manifest_file), true);
        $assets = $manifest['assets'];

        wp_enqueue_style('fonts', get_theme_file_uri( $assets['fonts.css'] ), [], $manifest["buildTimestamp"] );
        wp_enqueue_style('main_style', get_theme_file_uri( $assets['main_css.css'] ), [], $manifest["buildTimestamp"] );
        
        if ( is_page_template( 'templates/expert-page.php' ) ) {
            wp_enqueue_style('team_style', get_theme_file_uri( $assets['team_css.css'] ), ['main_style'], $manifest["buildTimestamp"] );
        }
        if ( is_page_template( 'templates/gutenberg.php' ) ) {
            wp_enqueue_style('gutenberg_css', get_theme_file_uri( $assets['gutenberg_css.css'] ), ['main_style'], $manifest["buildTimestamp"] );
        }
        
        wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', [], '1.11.0', true);
        wp_enqueue_script('main_js', get_theme_file_uri( $assets['main_js.js'] ), [], $manifest["buildTimestamp"], true);
    }
    wp_localize_script(
        'main_js',
        'ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ) 
        ) 
    );

}

add_action('wp_enqueue_scripts', 'custom_theme_assets');
/**
 * Like get_template_part() put lets you pass args to the template file
 * Args are available in the tempalte as $template_args array
 * @param string filepart
 * @param mixed wp_args style argument list
 */
function hm_get_template_part($file, $template_args = array(), $cache_args = array())
{
    $template_args = wp_parse_args($template_args);
    $cache_args = wp_parse_args($cache_args);
    if ($cache_args) {
        foreach ($template_args as $key => $value) {
            if (is_scalar($value) || is_array($value)) {
                $cache_args[$key] = $value;
            } elseif (is_object($value) && method_exists($value, 'get_id')) {
                $cache_args[$key] = call_user_method('get_id', $value);
            }
        }
        if (($cache = wp_cache_get($file, serialize($cache_args))) !== false) {
            if (!empty($template_args['return'])) {
                return $cache;
            }
            echo $cache;
            return;
        }
    }
    $file_handle = $file;
    do_action('start_operation', 'hm_template_part::' . $file_handle);
    if (file_exists(get_stylesheet_directory() . '/' . $file . '.php')) {
        $file = get_stylesheet_directory() . '/' . $file . '.php';
    } elseif (file_exists(get_template_directory() . '/' . $file . '.php')) {
        $file = get_template_directory() . '/' . $file . '.php';
    }
    ob_start();
    $return = require $file;
    $data = ob_get_clean();
    do_action('end_operation', 'hm_template_part::' . $file_handle);
    if ($cache_args) {
        wp_cache_set($file, $data, serialize($cache_args), 3600);
    }
    if (!empty($template_args['return'])) {
        if ($return === false) {
            return false;
        } else {
            return $data;
        }
    }
    echo $data;
}

function custom_dropdown_values($tag, $unused)
{
    if ($tag['name'] != 'industry-dropdown') {
        return $tag;
    }

    $args = array(
        'numberposts' => -1,
        'post_type' => 'industry',
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $custom_posts = get_posts($args);

    if (!$custom_posts) {
        return $tag;
    }

    foreach ($custom_posts as $custom_post) {
        $tag['raw_values'][] = $custom_post->post_title;
        $tag['values'][] = $custom_post->post_title;
        $tag['labels'][] = $custom_post->post_title;
    }
    return $tag;
}
add_filter('wpcf7_form_tag', 'custom_dropdown_values', 10, 2);

add_filter('shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3);

function custom_shortcode_atts_wpcf7_filter($out, $pairs, $atts)
{
    $my_attr = 'industry-dropdown';

    if (isset($atts[$my_attr])) {
        $out[$my_attr] = $atts[$my_attr];
    }

    return $out;
}

function svg($class, $title, $id)
{
    $uri = get_stylesheet_directory_uri() . '/images/svg/sprite.svg';
    if ($class) {
        $class = 'class="' . $class . '"';
    } else {
        $class = "";
    }
    if ($title) {
        $title = '<title>' . $title . '</title>';
    } else {
        $title = "";
    }
    return '<svg ' . $class . ' >' . $title . '<use xlink:href="' . $uri . '#' . $id . '"></use></svg>';
}

function activeNavbar()
{
    ?>
    <script>
    $(document).ready(function($){
        $(window).on('scroll', function(){
            if ($(window).scrollTop() > 10) {
                $('.navbar').addClass('active');
            }else{
                $('.navbar').removeClass('active');
            }
        });
    });
    </script>
<?php
}
add_action('wp_footer', 'activeNavbar');

// Custom logo
function themename_custom_logo_setup()
{
    $defaults = array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support('post-thumbnails');
    // Add support for Block Styles.
    add_theme_support( 'wp-block-styles' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );
}

add_action('after_setup_theme', 'themename_custom_logo_setup');

add_filter('get_custom_logo', 'change_logo_class');

function change_logo_class($html)
{

    // $html = str_replace( 'custom-logo', 'your-custom-class', $html );
    $html = str_replace('custom-logo-link', 'navbar-brand', $html);

    return $html;
}

/**
 * Hover dropdown submenu
 */
function toggle_dropdown()
{
    ?>
    <script>
    $(document).ready(function($){
        $('body')
            // .on('mouseenter mouseleave','#navbarNav .nav-item', toggleDropdown)
            .on('click', '.dropdown-menu a', toggleDropdown);
    });

    function toggleDropdown(e){
        const _d = $(e.target).closest('#navbarNav .nav-item'),
            _m = $('.dropdown-menu', _d);
        setTimeout(function(){
            const shouldOpen = e.type !== 'click' && _d.is(':hover');
            _m.toggleClass('show', shouldOpen);
            _d.toggleClass('show', shouldOpen);
            //$('[data-toggle="dropdown"]', _d).attr('aria-expanded', shouldOpen);
        }, e.type === 'mouseleave' ? 300 : 0);
    }
    </script>
<?php
}
add_action('wp_footer', 'toggle_dropdown');

function add_link_atts($atts, $item, $args)
{
    $atts['class'] = "nav-link";
    if ($args->menu == 'Top Submenu') {
        $atts['class'] = "nav-link py-0";
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_link_atts', 10, 3);

function special_nav_class($classes, $item)
{
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'dropdown ';
    }
    if (in_array('menu-item', $classes)) {
        $classes[] = 'nav-item ';
    }
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'active ';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

function get_expert($id)
{
    $post = get_post($id);

    return $post;
}

function get_experts()
{
    $query = new WP_Query(array(
        'post_type' => 'expert',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
    ));

    wp_reset_query();

    return $query->get_posts();
}

// get experts related to the current expert in same industries
function get_experts_by_term($id)
{
    $expert = get_expert($id);
    $meta = get_post_meta($expert->ID);

    $industry_expertise = [];
    if (isset($meta['industry_expertise'][0])) {
        $industry_expertise = unserialize($meta['industry_expertise'][0]);
    }

    $query = new WP_Query(array(
        'post__not_in' => array($id),
        'post_type' => 'expert',
        'post_status' => 'publish',
        'posts_per_page' => 20,
        /*
        'meta_query' => [
            [
                'key' => 'industry_expertise',
                'compare' => '=',
            ]
        ]
        */
    ));

    wp_reset_query();

    $experts = $query->get_posts();
    $new_experts = [];

    foreach ($experts as $expert) {
        $meta = get_post_meta($expert->ID);
        if (isset($meta['industry_expertise'][0])) {
            $industries = unserialize($meta['industry_expertise'][0]);
            if (is_array($industries)) {
                foreach ($industries as $industry) {
                    if (in_array($industry, $industry_expertise)) {
                        if (!in_array($expert, $new_experts)) {
                            $new_experts[] = $expert;
                        }

                        continue;
                    }
                }
            }
        }
    }
    return $new_experts;
}

function get_industries()
{
    $query = new WP_Query(array(
        'post_type' => 'industry',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));

    return $query->get_posts();
}

function get_locations()
{
    $query = new WP_Query(array(
        'post_type' => 'location',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));

    return $query->get_posts();
}

function get_childs_by_parent($parent_ID)
{
    $query = new WP_Query(array(
        'post_parent' => $parent_ID,
        'post_type' => 'page',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ));

    return $query->get_posts();
}

function get_custom_single_template($single_template)
{
    global $post;

    if ($post->post_type == 'resource') {
        $terms = get_the_terms($post->ID, 'resource-type');
        if ($terms && !is_wp_error($terms)) {
            //Make a foreach because $terms is an array but it supposed to be only one term
            foreach ($terms as $term) {
                if (file_exists(dirname(__FILE__) . '/single-resource-type-' . $term->slug . '.php')) {
                    $single_template = dirname(__FILE__) . '/single-resource-type-' . $term->slug . '.php';
                }
            }
        }
    }
    return $single_template;
}

add_filter("single_template", "get_custom_single_template");

function get_latest_post($posttype, $pp)
{
    $query = new WP_Query(array(
        'post_type' => $posttype,
        'post_status' => 'publish',
        'posts_per_page' => $pp,
        'order' => 'DESC',
        'orderby' => 'ID',
    ));
    return $query->get_posts();
}

//ACF add Footer & Header option page (admin)
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
    acf_add_options_sub_page('Header');
    acf_add_options_sub_page('Footer');
}

function limit_text($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

function _rm_search_enter()
{
    ?>
    <script>
    $('#quicksearch').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    </script>
<?php
}
add_action('wp_footer', '_rm_search_enter');

function _crypt($string, $action = 'e')
{
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function capital_meta_title($title)
{
    return ucwords(strtolower($title));
}

add_filter('wpseo_title', 'capital_meta_title');

add_action('wp_ajax_expert_filter', 'expert_filter');
add_action('wp_ajax_nopriv_expert_filter', 'expert_filter');

function expert_filter(){
    $meta_query = [];

    if (!empty($_POST['location'])) {
        $meta_query[] = [
            'key'     => 'location',
            'value'   => sanitize_text_field($_POST['location']),
            'compare' => '='
        ];
    }

    if (!empty($_POST['sector'])) {
        $meta_query[] = [
            'key'     => 'industry_expertise',
            'value'   => sanitize_text_field($_POST['sector']),
            'compare' => 'LIKE'
        ];
    }

    $args = [
        'posts_per_page' => -1,
        'post_type'      => 'expert',
        's'              => isset($_POST['custom_input']) ? sanitize_text_field($_POST['custom_input']) : '',
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ];

    if(count($meta_query) > 1) {

        $meta_query > array_merge(['relation', 'OR'], $meta_query);

    }

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);

    ob_start();

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
        $profile_image = get_field('profile_image');
        //var_dump($profile_image);
    ?>
        <div class="col-md-4 mb-5">
            <div class="team-box-inner">
                <div class="team-img">
                    <a href="<?= esc_url(get_permalink()) ?>">
                        <?php echo wp_get_attachment_image ( 
                            $profile_image['id'],
                            'thumbnail', 
                            false, 
                            [
                                "class" => "team-img-single",
                                "alt"=> esc_attr($query->post_title)
                            ] 
                        ); ?>                
                    </a>
                </div>
                <div class="member-name">
                    <a href="<?= esc_url(get_permalink()) ?>"><?= get_the_Title(); ?></a>
                </div>
                <div class="member-desigantion"><?php echo get_field('title',$query->ID); ?></div>
                <div class="member-address"><?php echo get_field('location',$query->ID)->post_title; ?></div>
                <div class="social-icons text-center">
                    <ul class="experts-socials p-0 align-items-center">
                        
                    <?php if($email = get_field('email',$query->ID)){ ?>
                        <li><a href="mailto:<?= $email; ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                    <?php } ?>
                    <?php if($contact = get_field('contact_no',$query->ID)){ ?>
                        <li><a href="tel:<?= $contact; ?>"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
                    <?php } ?>
                    <?php if($linkedin = get_field('linkedin',$query->ID)){ ?>
                        <li><a href="<?= $linkedin;?>" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php
            endwhile;
            wp_reset_postdata();
        else:
            echo 'No Expert Found.';
        endif;

    echo ob_get_clean();
    die();

}