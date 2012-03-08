<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title>
    <?php bloginfo( 'name' ); ?>
    </title>
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.png" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<script type="text/javascript" src="<?php echo bloginfo('stylesheet_directory') ?>/js/jquery-1.7.1.min.js"></script>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<script type="text/javascript">

var currentImage;
    var currentIndex = -1;
    var interval;
    function showImage(index){
        if(index < $('#bigPic img').length){
        	var indexImage = $('#bigPic img')[index]
            if(currentImage){   
            	if(currentImage != indexImage ){
                    $(currentImage).css('z-index',2);
                    clearTimeout(myTimer);
                    $(currentImage).fadeOut(250, function() {
					    myTimer = setTimeout("showNext()", 3000);
					    $(this).css({'display':'none','z-index':1})
					});
                }
            }
            $(indexImage).css({'display':'block', 'opacity':1});
            currentImage = indexImage;
            currentIndex = index;
            $('#thumbs li').removeClass('active');
            $($('#thumbs li')[index]).addClass('active');
			$('#list li').removeClass('active');
            $($('#list li')[index]).addClass('active');
        }
    }
    
    function showNext(){
        var len = $('#bigPic img').length;
        var next = currentIndex < (len-1) ? currentIndex + 1 : 0;
        showImage(next);
    }
    
    var myTimer;
    $(document).ready(function() {
	    myTimer = setTimeout("showNext()", 3000);
		showNext(); //loads first image
        $('#thumbs li').bind('click',function(e){
        	var count = $(this).attr('rel');
        	showImage(parseInt(count)-1);
        });
	});
    
    
	
	</script>
	
<?php wp_head();?>
<title><?php bloginfo( 'name' ); ?></title>
</head>
<body>
<div class="wraper">
	<div class="container">
		<div class="header">
   			<div class="menu-header">
           		<div class="line"></div>
     			<a href="<?php echo get_settings('home'); ?>"><div class="logo"></div></a>
     			<div class="top-menu">
               <?php wp_nav_menu(array( 'sort_column' => 'menu_order', 'menu' => 'top-menu', 'container_class' => 'top-menu', 'container_id' => '', 'theme_location'  => 'top-menu') ); ?>
                </div>
  			</div>
      <div class="slider">
    		<div id="bigPic"> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/1.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/2.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/3.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/4.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/5.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/6.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/7.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/8.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/9.jpg" /> 
<img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/10.jpg" /> 
			</div>
<div class="button-slider">
			<div class="list"><ul id="list">
				<li class="active" rel="1">1</li>
				<li rel="2">2</li>
				<li rel="3">3</li>
                <li rel="4">4</li>
                <li rel="5">5</li>
                <li rel="6">6</li>
                <li rel="7">7</li>
                <li rel="8">8</li>
                <li rel="9">9</li>
                <li rel="10">10</li>
			</ul>
			</div>
             <div>
           <ul id="thumbs">
            <li class="active" rel="1"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/1_thumb.jpg" /> </li>
            <li rel="2"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/3_thumb.jpg" /> </li>
            <li rel="3"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/4_thumb.jpg" /> </li>
            <li rel="4"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/5_thumb.jpg" /> </li>
            <li rel="5"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/6_thumb.jpg" /> </li>
            <li rel="6"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/7_thumb.jpg" /> </li>
            <li rel="7"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/8_thumb.jpg" /> </li>
            <li rel="8"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/9_thumb.jpg" /> </li>
            <li rel="9"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/10_thumb.jpg" /> </li>
            <li rel="10"><img alt="" src="<?php bloginfo('template_directory'); ?>/imgs/2_thumb.jpg" /> </li>
           </ul>
          </div>
          <div class="bottom-slider"></div>
</div>
</div>
</div>


