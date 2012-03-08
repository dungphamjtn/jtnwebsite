<?php
$info=array(
	'name' => 'Header&Footer',
	'pagename' => 'header-footer-options',
	'title' => 'Header & Footer',
	'sublevel' => 'yes'
);

$options=array(
array(
	"type" => "text",
	"name" => "Top box - heading 1",
	"id" => RM_SHORT."box_heading1",
	"desc" => "Enter the heading for the box near the top, #1",
	"default" => "" ),

array(
	"type" => "textarea",
	"name" => "Top box - content 1",
	"id" => RM_SHORT."box_content1",
	"desc" => "Enter the content for the box near the top, #1",
	"default" => "" ),	
	
array(
	"type" => "image",
	"name" => "Top box - icon 1",
	"id" => RM_SHORT."box_icon1",
	"desc" => "Enter the icon(about 71x70px, will be resized) for the box near the top, #1",
	"default" => "" ),
	
array(
	"type" => "text",
	"name" => "Top box - heading 2",
	"id" => RM_SHORT."box_heading2",
	"desc" => "Enter the heading for the box near the top, #2",
	"default" => "" ),

array(
	"type" => "textarea",
	"name" => "Top box - content 2",
	"id" => RM_SHORT."box_content2",
	"desc" => "Enter the content for the box near the top, #2",
	"default" => "" ),	
	
array(
	"type" => "image",
	"name" => "Top box - icon 2",
	"id" => RM_SHORT."box_icon2",
	"desc" => "Enter the icon(about 72x70px, will be resized) for the box near the top, #2",
	"default" =>"" ),	

array(
	"type" => "text",
	"name" => "Top box - heading 3",
	"id" => RM_SHORT."box_heading3",
	"desc" => "Enter the heading for the box near the top, #3",
	"default" => "" ),

array(
	"type" => "textarea",
	"name" => "Top box - content 3",
	"id" => RM_SHORT."box_content3",
	"desc" => "Enter the content for the box near the top, #3",
	"default" => "" ),	
	
array(
	"type" => "image",
	"name" => "Top box - icon 3",
	"id" => RM_SHORT."box_icon3",
	"desc" => "Enter the icon(about 73x70px, will be resized) for the box near the top, #3",
	"default" => "" ),

array(
	"type" => "textarea",
	"name" => "Footer copyright",
	"id" => RM_SHORT."footer_copyright",
	"desc" => "Enter the message to display on the right side of the footer",
	"default" => "" ),

array(
	"type" => "checkbox",
	"name" => "Display footer nav?",
	"id" => array( RM_SHORT."footer_nav"),
	"options" => array( "Show"),					
	"desc" => "Check this to display navigation in the footer",
	"default" => array( "checked") ),	
	
);

$optionspage=new rm_options_page($info, $options);
?>