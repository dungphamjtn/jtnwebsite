<?php
$info=array(
	'name' => 'Portfolio & Blog',
	'pagename' => 'portfolio-blog-options',
	'title' => 'Portfolio & Blog',
	'sublevel' => 'yes'
);



$all_categories=get_all_category_ids();
$cat_list = array();
$cat_options=array();
$checked_cats=array();
foreach ($all_categories as $cat_id ) {
	$cat_object=get_category($cat_id);
    $cat_list[] = "rm_cat_".str_replace(' ', '_', $cat_object->slug);
    $cat_options[] = $cat_object->cat_name;
	$checked_cats[]="checked";
}


$options=array(

array(
	"type" => "text",
	"name" => "Portfolio posts per page",
	"id" => RM_SHORT."portfolio_page",
	"desc" => "Enter the number of posts you want to display on each portfolio page. Default is 6",
	"default" => "6" ),	
	
array(
	"type" => "checkbox",
	"name" => "Display author bio?",
	"id" => array( RM_SHORT."author_bio"),
	"options" => array( "Show"),					
	"desc" => "Check this to display the author bio under the blog entry",
	"default" => array( "checked") ),
	
array(
	"type" => "checkbox",
	"name" => "Display top box on blog pages?",
	"id" => array( RM_SHORT."blog_box"),
	"options" => array( "Show"),					
	"desc" => "Check this to display the top box on blog pages",
	"default" => array( "checked") ),
array(
	"type" => "checkbox-nav",
	"name" => "Exclude Categories",
	"id" => $cat_list,
	"options" => $cat_options,	
	"desc" => "Select which categories to display and exclude from the blog on the home page and main blog page",
	"default" => $checked_cats )
	
);

$optionspage=new rm_options_page($info, $options);
?>