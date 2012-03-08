<?php


//BUILD IMAGE RESIZE
function build_image($img='', $width=100, $height=100, $zc=1){

$build='';

//If timthumb is disabled
if(get_option(RM_SHORT.'enable_timthumb') and get_option(RM_SHORT.'enable_timthumb')=='false')
	return $img;
	
$build=RM_THEME_DIR."/php/timthumb.php?src=$img&amp;w=$width&amp;h=$height&amp;zc=$zc";

return $build;

}



//BREADCRUMBS
function rm_breadcrumbs(){
	global $post;
?>
	You are here: <a href="<?php bloginfo('url'); ?>" style="font-weight: bold;">Home</a>

<?php
	if(is_front_page())
		return;
echo ' / ';

	if ( is_home() ) {
			echo 'Blog';
	}

	if ( is_page() && !$post->post_parent ) {
			the_title();
	}
	elseif( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb)
				echo $crumb . ' / ';
			the_title();
	}
	elseif (is_category() ) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' / '));
			echo 'Archive of category &#39;';
			single_cat_title();
			echo '&#39;';
 
	}
	elseif ( is_day() ) {
    	echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> / ';
    	echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> / ';
    	echo get_the_time('d');
	} 
	elseif ( is_month() ) {
    	echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> / ';
    	echo get_the_time('F'); 
	} 
	elseif ( is_year() ) {
    	echo get_the_time('Y'); 
	} 
	elseif ( is_single() ) {
			the_title();
	}
	elseif ( is_search() ) {
			echo 'Search results for &#39;' . get_search_query() . '&#39;'; 
	}
	elseif ( is_tag() ) {
			echo 'Posts tagged &#39;';
			single_tag_title();
			echo '&#39;';
	}
	
	if ( get_query_var('paged') ) {			
			echo '( Page' . ' ' . get_query_var('paged'). ' )';
			
	}
	
}


//CREATE LIST OF PAGES TO EXCLUDE
function rm_build_excludepage($prefix){
	$prefix.="_";
	$pages=get_pages();
	$exclude="";
	
	foreach($pages as $pg):
		$page_field=$prefix . str_replace(' ', '_', $pg->post_name);
		if( get_option($page_field)=='false') //if the page isn't to be displayed, add its ID to exclude
			$exclude.=$pg->ID . ","; 
	endforeach;
	
	
	if($exclude!="")
		$exclude=substr($exclude, 0, -1); //exclude final comma
		
	return $exclude;
}



//CREATE LIST OF CATEGORIES TO EXCLUDE
function rm_build_excludecat($prefix){
	$prefix.="_";
	$categories = get_categories('hide_empty=0&orderby=id');
	$exclude=array();
	
	foreach($categories as $cat):
		$cat_field=$prefix . str_replace(' ', '_', $cat->slug);
		$cat_slug=$cat->slug;
		if( get_option($cat_field) and get_option($cat_field)=='false'){
			$cat_id=get_term_by( 'slug', $cat_slug, 'category' );
			$cat_id=$cat_id->term_id;
			$exclude[]=$cat_id;
			}
	endforeach;	
	
		
	return $exclude;
}

//DISPLAY MAIN MENU
function rm_mainmenu(){
	
	$excludes=rm_build_excludepage(RM_SHORT.'page'); //get pages to be excluded	
	if($excludes!="")
		$excludes='&exclude='.$excludes;
	
	wp_list_pages('title_li=&depth=3'.$excludes);

}

//DISPLAY FOOTER MENU
function rm_footermenu(){
	
	$excludes=rm_build_excludepage(RM_SHORT.'footer_page'); //get pages to be excluded	
	if($excludes!="")
		$excludes='&exclude='.$excludes;
	
	wp_list_pages('title_li=&depth=1'.$excludes);

}


	
?>