<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<!-- Gọi style.css -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if(function_exists('automatic_feed_links')) automatic_feed_links(); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<!-- Gọi các Actions tại điểm móc 'wp_head' -->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page">
	<div id="header" role="banner">
		<div id="headerimg">
			<p>Header</p>
			<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
			<div class="description">
				<?php bloginfo('description'); ?>
				<?php
					if ( is_single() ) _e('Đây là một bài viết');
					elseif ( is_page() ) _e('Đây là một trang');
				?>
			</div>
		</div>
	</div>