<?php

function wp_register_custom_post_type() {
	register_post_type('expert',
		array(
			'labels'      => array(
				'name'          => __( 'Experts', 'textdomain' ),
				'singular_name' => __( 'Expert', 'textdomain' ),
			),
			'public'      => true,
			'has_archive' => true,
			'show_in_rest' => true,
		)
	);

	register_post_type('industry',
		array(
			'labels'      => array(
				'name'          => __( 'Industries', 'textdomain' ),
				'singular_name' => __( 'Industry', 'textdomain' ),
			),
			'public'      => true,
			'has_archive' => true,
			'show_in_rest' => true,
		)
	);

	register_post_type('Location',
		array(
			'labels'      => array(
				'name'          => __( 'Locations', 'textdomain' ),
				'singular_name' => __( 'Location', 'textdomain' ),
			),
			'public'      => true,
			'has_archive' => true,
			'show_in_rest' => true,
		)
	);
}

add_action('init', 'wp_register_custom_post_type');

?>