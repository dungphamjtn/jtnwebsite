<?php

function rm_titles(){

$separator="|";
if(get_option(RM_SHORT.'seo_separator'))
	$separator=stripslashes(get_option(RM_SHORT.'seo_separator'));

	$separator=" ".$separator;

//HOME PAGE:
if(is_front_page()){
		if (get_option(RM_SHORT.'seo_home_title') == 'Name | Description')
			echo get_bloginfo('name').$separator.get_bloginfo('description'); 
		else if ( get_option(RM_SHORT.'seo_home_title') == 'Description | Name') 
			echo get_bloginfo('description').$separator.get_bloginfo('name');
		else 
			echo get_bloginfo('name');
}
//POST OR PAGE:
else if(is_single() or is_page() or is_home()){
		if (get_option(RM_SHORT.'seo_page_title') == 'Name | Postname')
			echo get_bloginfo('name').$separator.wp_title('', 0); 
		else if ( get_option(RM_SHORT.'seo_page_title') == 'Postname | Name') 
			echo wp_title('', 0).$separator.get_bloginfo('name');
		else 
			echo get_bloginfo('name');
}

//INDEX PAGES			
else if(is_category() or is_archive() or is_search() or is_tag()){
		if (get_option(RM_SHORT.'seo_index_title') == 'Name | Category')
			echo get_bloginfo('name').$separator.wp_title('', 0); 
		else if ( get_option(RM_SHORT.'seo_index_title') == 'Category | Name') 
			echo wp_title('', 0).$separator.get_bloginfo('name');
		else 
			echo get_bloginfo('name');
}
			
//404 ERROR
else if ( is_404() ){
		echo get_bloginfo('name');
		echo ($separator.'404 Error (Page Not Found)');
}

//FALLBACK
else{
		echo get_bloginfo('name').$separator.wp_title('', 0);
}

}

?>