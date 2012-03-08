<?php
$info=array(
	'name' => 'SEO',
	'pagename' => 'seo-options',
	'title' => 'SEO',
	'sublevel' => 'yes'
);

$options=array(

array(
	"type" => "select",
	"name" => "Homepage title",
	"id" => RM_SHORT."seo_home_title",
	"options" => array("Only Blogname", "Name | Description", "Description | Name"),
	"desc" => "Choose a way for your home page title to be displayed.",
	"default" => "Only Blogname" ),
	
array(
	"type" => "select",
	"name" => "Post/page titles",
	"id" => RM_SHORT."seo_page_title",
	"options" => array("Only Blogname", "Name | Postname", "Postname | Name"),
	"desc" => "Choose a way for your individual post/page titles to be displayed.",
	"default" => "Only Blogname" ),
	
array(
	"type" => "select",
	"name" => "Index page titles titles",
	"id" => RM_SHORT."seo_index_title",
	"options" => array("Only Blogname", "Name | Category", "Category | Name"),
	"desc" => "Choose a way for your index page(archive, category, tag, search etc) titles to be displayed.",
	"default" => "Only Blogname" ),


array(
	"type" => "text",
	"name" => "SEO separator",
	"id" => RM_SHORT."seo_separator",
	"desc" => "Enter a separator for your titles. Default is '|'",
	"default" => "|" ),
	
array(
	"type" => "text",
	"name" => "Homepage meta keywords",
	"id" => RM_SHORT."meta_keywords",
	"desc" => "Enter meta keywords to display on your home page. Leave it blank for no keywords.",
	"default" => "" ),
	
array(
	"type" => "textarea",
	"name" => "Homepage meta descriptions",
	"id" => RM_SHORT."meta_description",
	"desc" => "Enter a meta description to display on your home page. Leave it blank for no description.",
	"default" => "" )
	
);

$optionspage=new rm_options_page($info, $options);
?>