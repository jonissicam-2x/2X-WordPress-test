<?php
/**
 * Template Name: Gutenberg Supported Page
 * Description: A clean, containerized template built specifically to display Gutenberg blocks correctly.
 */

get_header(); ?>

<main id="main" class="site-main">

    <?php
    // Start the WordPress Loop
    while ( have_posts() ) :
        the_post();
        $has_no_cover = ! has_block( 'core/cover' ) && ! has_block( 'core/image' );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('gutenberg-content'); ?>>
            
            <?php if ( $has_no_cover ) : ?>
                <!-- Optional: Only show the standard title if a block hero/image isn't being used at the top -->
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="entry-content is-layout-constrained<?php if ( $has_no_cover ){ echo ' wp-site-blocks';} ?>">
                <?php
                // This is the crucial function that echoes all Gutenberg block output
                the_content(); 
                ?>
            </div>

        </article>

    <?php
    endwhile; // End of the loop.
    ?>

</main>

<?php
get_footer();
