<!-- The WordPress Primary Menu -->
<?php wp_nav_menu(
    [
        'theme_location' => 'below_footer',
        'menu_class' => 'list-unstyled',
        'container_class' => 'nav-pipe',
        'container_id' => 'navBelowFooter',
    ]
);?>
<p class="copy_text">Copyright &copy; <?php echo date('Y'); ?> The Ironclaw Syndicate. All rights reserved.</p>
<p class="fst-italic word-spacing-normal">Unauthorized scraping of this database will result in immediate deployment of a Tier 3 tracking unit. Trespassers will be scratched.</p>
