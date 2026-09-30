<?php
global $post, $have_bg;

//echo get_the_title($post->post_parent);

$body_height = null;
$haveBg = array('industry', 'expert', 'resource');

if (in_array(get_post_type(), $haveBg)):
    $the_bar = (!is_user_logged_in()) ? '' : '-bar';
    $body_height = 'bodyTop' . $the_bar;
    $have_bg = 'have-bg';
endif;

?>

<!DOCTYPE html>
<html <?php language_attributes();?>>

<head>
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri() . '/images/favicon.ico' ?>">
    <?php wp_head()?>
    
</head>

<body <?php body_class($body_height);?>>

<?php get_template_part('template-parts/navigation/navigation', 'top');?>

<div id="main">