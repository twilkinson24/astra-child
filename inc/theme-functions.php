<?php

// Change default excerpt
function rx_new_excerpt_read_more($more) {
	global $post;
	return '<a class="rx-read-more" href="'. get_permalink($post->ID) . '">Meet ' . get_the_title($post->ID) . '</a>';
}
add_filter('excerpt_more', 'rx_new_excerpt_read_more');



// Disable Featured image on all post types.
function rx_featured_image() {
    $post_types = array('post', 'page');
   
    // bail early if the current post type if not the one we want to customize.
    if ( ! in_array( get_post_type(), $post_types ) ) {
    return;
    }
    
    // Disable featured image.
    add_filter( 'astra_featured_image_enabled', '__return_false' );
   }
   
   add_action( 'wp', 'rx_featured_image' );





   ///* I was told to remove sign in/sign out link from menu */
function add_account_options_to_menu($items, $menu) {
	if($menu->menu_id == 'primary-menu') {
		if(is_user_logged_in()) {
			$items .= "<li class='menu-item'><a class='menu-link' href='".wp_logout_url($_SERVER['REQUEST_URI'])."'>Sign Out</a></li>";
		} else { // not logged in
			$items .= "<li class='menu-item'><a class='menu-link' href='". '/interviews/login' ."'>Sign In</a></li>";
		}
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'add_account_options_to_menu', 10, 2);