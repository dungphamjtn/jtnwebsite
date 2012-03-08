<?php
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Sidebar - Pages',
						'before_widget' => '<div class="sidebar_item">',
						'after_widget'  => '</div>',
						'before_title'  => '<h3>',
						'after_title'   => '</h3>' )
						);
	}
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Sidebar - Blog',
						'before_widget' => '<div class="sidebar_item">',
						'after_widget'  => '</div>',
						'before_title'  => '<h3>',
						'after_title'   => '</h3>' )
						);
	}
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Footer Widget 1',
						'before_widget' => '',
						'after_widget'  => '',
						'before_title'  => '<h6>',
						'after_title'   => '</h6>' )
						);
	} 
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Footer Widget 2',
						'before_widget' => '',
						'after_widget'  => '',
						'before_title'  => '<h6>',
						'after_title'   => '</h6>' )
						);
	} 
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Footer Widget 3',	
						'before_widget' => '',
						'after_widget'  => '',
						'before_title'  => '<h6>',
						'after_title'   => '</h6>' )
						);
	} 
	if(function_exists('register_sidebar')){
		register_sidebar(array(
						'name'          => 'Footer Widget 4',
						'before_widget' => '',
						'after_widget'  => '',
						'before_title'  => '<h6>',
						'after_title'   => '</h6>' )
						);
	} 
	
?>
