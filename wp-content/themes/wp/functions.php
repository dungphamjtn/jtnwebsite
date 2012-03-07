<?php
//
//add favicon website
add_filter('genesis_favicon_url', 'custom_favicon_url');
function custom_favicon_url() {
	$favicon = dirname( get_bloginfo('stylesheet_url') ) . "/favicon.ico";
	return $favicon;
}

//menu items css
add_filter( 'nav_menu_css_class', 'additional_active_item_classes', 10, 2 ); function additional_active_item_classes($classes = array(), $menu_item = false){   //add current menu order to item classes    $classes[] = "item-".$menu_item--->menu_order;
    return $classes;
}
// insert background
if ( function_exists('add_custom_background') ) {
add_custom_background();
}



//registry menu to theme
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}
function register_my_menus() {
	register_nav_menus(
		array(
			'top-menu' => __( 'Top Menu' )
		)
	);
}
//registry side bar
register_sidebar( array(
		'name' => __( 'Main Sidebar' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

register_sidebar( array(
		'name' => __( 'Footer 1' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
register_sidebar( array(
		'name' => __( 'Footer 2' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
register_sidebar( array(
		'name' => __( 'Footer 3' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
register_sidebar( array(
		'name' => __( 'Footer 4' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );