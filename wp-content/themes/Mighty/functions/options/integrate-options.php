<?php
$info=array(
	'name' => 'Integration',
	'pagename' => 'code-integration',
	'title' => 'Code Integration',
	'sublevel' => 'yes'
);

$options=array(

array(
	"type" => "checkbox",
	"name" => "Add these",
	"id" => array( RM_SHORT."header_js_code", RM_SHORT."footer_js_code", RM_SHORT."css_code", RM_SHORT."child_stylesheet"),
	"options" => array( "Add JavaScript to &lt;head&gt;", "Add JavaScript to &lt;footer&gt;", "Add custom CSS", "Add child stylesheet"),					
	"desc" => "Choose whether to display the code below in the given locations. You can uncheck the box and let the code remain in the boxes below to preserve it.",
	"default" => array( "checked", "checked", "checked") ),

array(
	"type" => "textarea",
	"name" => "Header Javascript",
	"id" => RM_SHORT."header_js",
	"desc" => "Enter any Javascript you may want for the header. You MUST enter &lt;script&gt; tags",
	"default" => "" ),
	
array(
	"type" => "textarea",
	"name" => "Footer Javascript",
	"id" => RM_SHORT."footer_js",
	"desc" => "Enter any Javascript you may want for the footer. You MUST enter &lt;script&gt; tags",
	"default" => "" ),
	
array(
	"type" => "textarea",
	"name" => "Custom CSS",
	"id" => RM_SHORT."custom_css",
	"desc" => "Enter any custom CSS you want. You MUST NOT enter &lt;style&gt; tags",
	"default" => "" ),
	
array(
	"type" => "text",
	"name" => "Child stylesheet",
	"id" => RM_SHORT."child_css",
	"desc" => "Enter the URL of a child stylesheet to display.",
	"default" => "" )
	
);

$optionspage=new rm_options_page($info, $options);
?>