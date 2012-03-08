<?php
$info=array(
	'name' => 'general',
	'pagename' => 'general-options',
	'title' => 'General & Homepage settings'
);

$options=array(

array(
	"type" => "image",
	"name" => "Logo",
	"id" => RM_SHORT."logo",
	"desc" => "Paste the URL to your logo, or upload it here.",
	"default" => get_bloginfo('template_directory')."/images/logo.png" ),
	
array(
	"type" => "image",
	"name" => "Favicon",
	"id" => RM_SHORT."favicon",
	"desc" => "Paste the URL to your favicon, or upload it here.",
	"default" => "" ),
	
array(
	"type" => "checkbox",
	"name" => "Show Login link?",
	"id" => array( RM_SHORT."login_link"),
	"options" => array( "Show"),					
	"desc" => "Check this to display a 'login' link in the admin panel for direct login into the admin panel.",
	"default" => array( "checked") ),	
	
array(
	"type" => "text",
	"name" => "Homepage blog posts",
	"id" => RM_SHORT."home_posts",
	"desc" => "Enter the number of images you want to display on the home page. Default is 3",
	"default" => "3" ),
	
array(
	"type" => "checkbox",
	"name" => "Display breadcrumbs?",
	"id" => array( RM_SHORT."show_breadcrumbs"),
	"options" => array( "Show"),					
	"desc" => "Check this to display breadcrumbs (ie. 'You are here: Home / About' etc)",
	"default" => array( "checked") ),	
	
array(
	"type" => "checkbox",
	"name" => "Display 'RSS' link?",
	"id" => array( RM_SHORT."show_rss"),
	"options" => array( "Show"),					
	"desc" => "Check this to display the 'Subscribe to RSS' link",
	"default" => array( "checked") ),	
	
array(
	"type" => "text",
	"name" => "Cufon file",
	"id" => RM_SHORT."cufon",
	"desc" => "Add the URL to an alternative Cufon (font replacement) file here.",
	"default" => get_bloginfo('template_directory')."/js/LiberationSans_Cufon.js" )
);

$optionspage=new rm_options_page($info, $options);
?>