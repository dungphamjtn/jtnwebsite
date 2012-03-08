<?php

//HOMEPAGE IMAGES
function shortcode_slider($atts, $content = null){

 extract( shortcode_atts( array(
      'image' => '',
      'link' => 'javascript:;',
	  'bg' => '#000',
      ), $atts ) );
	  
$the_image='';	  
if($image!='')
	$the_image="<img src='$image' alt='' />";


$the_link="<a href='$link'>$the_image</a>";

$the_li='<li>'.$the_link.'</li>';
if($bg!='')
$the_li="<li style='background: $bg'>$the_link</li>";

return $the_li;

}
add_shortcode('slider', 'shortcode_slider');


//INFO
function shortcode_info($atts, $content=null){
return '<p class="not_info"><span class="img"></span>'.$content.'<span class="close"></span></p>';	
}
add_shortcode('info', 'shortcode_info');


//SUCCESS
function shortcode_success($atts, $content=null){
return '<p class="not_success"><span class="img"></span>'.$content.'<span class="close"></span></p>';	
}
add_shortcode('success', 'shortcode_success');


//WARNING
function shortcode_warning($atts, $content=null){
return '<p class="not_warning"><span class="img"></span>'.$content.'<span class="close"></span></p>';	
}
add_shortcode('warning', 'shortcode_warning');


//ERROR
function shortcode_error($atts, $content=null){
return '<p class="not_error"><span class="img"></span>'.$content.'<span class="close"></span></p>';
}
add_shortcode('error', 'shortcode_error');


//HALF WIDTH
function shortcode_half($atts, $content=null){
return '<div class="x2">'.$content.'</div>';
}
add_shortcode('half', 'shortcode_half');

//HALF WIDTH LAST
function shortcode_half_last($atts, $content=null){
return '<div class="x2 no_margin">'.$content.'</div>';
}
add_shortcode('half_last', 'shortcode_half_last');


//ONE THIRD WIDTH
function shortcode_onethird($atts, $content=null){
return '<div class="x3">'.$content.'</div>';
}
add_shortcode('one_third', 'shortcode_onethird');

//ONE THIRD WIDTH LAST
function shortcode_onethird_last($atts, $content=null){
return '<div class="x3 no_margin">'.$content.'</div>';
}
add_shortcode('one_third_last', 'shortcode_onethird_last');


//ONE FOURTH WIDTH
function shortcode_onefourth($atts, $content=null){
return '<div class="x4">'.$content.'</div>';
}
add_shortcode('one_fourth', 'shortcode_onefourth');

//ONE FOURTH WIDTH LAST
function shortcode_onefourth_last($atts, $content=null){
return '<div class="x4 no_margin">'.$content.'</div>';
}
add_shortcode('one_fourth_last', 'shortcode_onefourth_last');


//TWO THIRD WIDTH 
function shortcode_twothird($atts, $content=null){
return '<div class="x3_2">'.$content.'</div>';
}
add_shortcode('two_third', 'shortcode_twothird');


//THREE FOURTH WIDTH
function shortcode_threefourth($atts, $content=null){
return '<div class="x4_3">'.$content.'</div>';
}
add_shortcode('three_fourth', 'shortcode_threefourth');


//CODE
function shortcode_code($atts, $content=null){
return '<code>'.$content.'</code>';
}
add_shortcode('code', 'shortcode_code');
?>