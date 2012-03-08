<?php
$info=array(
	'name' => 'Navigation',
	'pagename' => 'nav-options',
	'title' => 'Navigation',
	'sublevel' => 'yes'
);

$pages=get_pages();
$footer_pages=get_pages('parent=0');
$page_list_main=array();
$page_list_footer=array();
$page_options_main=array();
$page_options_footer=array();
$checked_pages_main=array();
$checked_pages_footer=array();
foreach ($pages as $pg ) {
    $page_list_main[] = RM_SHORT."page_".str_replace(' ', '_', $pg->post_name);
    $page_options_main[] = $pg->post_title;
	$checked_pages_main[]="checked";
}
foreach ($footer_pages as $pg ) {
    $page_list_footer[] = RM_SHORT."footer_page_".str_replace(' ', '_', $pg->post_name);
    $page_options_footer[] = $pg->post_title;
	$checked_pages_footer[]="checked";
}

$options=array(

array(
	"type" => "checkbox-nav",
	"name" => "Main Menu Pages",
	"id" => $page_list_main,
	"options" => $page_options_main,	
	"desc" => "Select which pages to display in the main page menu",
	"default" => $checked_pages_main ),
	
array(
	"type" => "checkbox-nav",
	"name" => "Footer Menu Pages",
	"id" => $page_list_footer,
	"options" => $page_options_footer,	
	"desc" => "Select which pages to display in the footer page navigation",
	"default" => $checked_pages_footer )
	
);

$optionspage=new rm_options_page($info, $options);
?>