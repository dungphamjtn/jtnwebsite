<?php
$info=array(
	'name' => 'Misc',
	'pagename' => 'misc-options',
	'title' => 'Miscellaneous',
	'sublevel' => 'yes'
);

$options=array(

array(
	"type" => "text",
	"name" => "Alternate RSS URL",
	"id" => RM_SHORT."rss_url",
	"desc" => "If you have an alternate RSS URl (eg Feedburner), enter it here.",
	"default" => "" ),
	
array(
	"type" => "textarea",
	"name" => "404 error message",
	"id" => RM_SHORT."404",
	"desc" => "Enter a message to display on your 404 (page not found) error pages.",
	"default" => "" ),

array(
	"type" => "checkbox",
	"name" => "Enable timthumb?",
	"id" => array( RM_SHORT."enable_timthumb"),
	"options" => array( "Show"),					
	"desc" => "Check this to allow dynamic TimThumb image resizing. Uncheck to do manual crops",
	"default" => array( "checked") )	
	

	
);

$optionspage=new rm_options_page($info, $options);
?>