<?php
get_header();

$id = get_the_ID();
$page = get_post($id);
?>
<?php

/**Hero */
hm_get_template_part('template-parts/hero', ['page' => $page]);

?>

<section class="bg bg-white <?=strtolower($page->post_title)?>">
    <div class="container no-pad-gutters">
        <div class="row">
            <div class="col-12 col-md-10">
                <h1 class="pb-4 text-uppercase"><?php echo $page->intro_title ?></h1>
                <h4 class="normal-font-regular"><?php echo $page->post_content ?></h4>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
