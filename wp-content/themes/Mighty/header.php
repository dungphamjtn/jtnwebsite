<?php
global $homepage;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<!-- head -->
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<!--Meta data -->
	<?php if(is_front_page()): ?>
		<?php if(get_option(RM_SHORT.'meta_keywords') and get_option(RM_SHORT.'meta_keywords')!=""): ?>
			<meta name="keywords" content="<?php echo stripslashes(get_option(RM_SHORT.'meta_keywords')); ?>" />
		<?php endif; ?>
		
		<?php if(get_option(RM_SHORT.'meta_description') and get_option(RM_SHORT.'meta_description')!=""): ?>
			<meta name="description" content="<?php echo stripslashes(get_option(RM_SHORT.'meta_description')); ?>" />
		<?php endif; ?>
	<?php endif; ?>
	
	
	<!-- Title of page -->
	<title><?php rm_titles(); ?></title>
	
	<!-- Main CSS Stylesheet -->
	<link href="<?php bloginfo('template_directory'); ?>/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />

<?php if(get_option(RM_SHORT.'favicon') and get_option(RM_SHORT.'favicon')!=""): ?>
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo stripslashes(get_option(RM_SHORT.'favicon')); ?>" />

<?php endif; ?>	

	<!-- .JS Files -->
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/Cufon_yui.js"></script>
	<?php
		$cufon=get_bloginfo('template_directory')."/js/LiberationSans_Cufon.js";
		if(get_option(RM_SHORT.'cufon') and get_option(RM_SHORT.'cufon')!="")
			$cufon=stripslashes(get_option(RM_SHORT.'cufon'));
	?>
	<script type="text/javascript" src="<?php echo $cufon; ?>"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/scripts.js"></script>
	
	<!--[if IE 6]>
	<script src="<?php bloginfo('template_directory'); ?>/js/DD_belatedPNG.js"></script>
	<script> DD_belatedPNG.fix('*'); </script>
	<style type="text/css"> #menu ul li a span { display: none; } </style>
	<![endif]-->
	
	<style type="text/css">	
		<?php if($homepage): ?>	
		<!--
			body { background: #ffffff url('<?php bloginfo('template_directory'); ?>/images/bg.gif') repeat-x center 313px; }	
		-->
		<?php endif;	?>	
		
	<?php 
		if(get_option(RM_SHORT.'css_code')=="true")
			echo stripslashes(get_option(RM_SHORT.'custom_css'));
	?>
	</style>
	
	<?php 
		if(get_option(RM_SHORT.'header_js_code')=="true")
			echo stripslashes(get_option(RM_SHORT.'header_js'));
	?>
	
	<?php 
		if(get_option(RM_SHORT.'child_stylesheet')=="true")
			echo '<link rel="stylesheet" href="'.stripslashes(get_option(RM_SHORT.'child_css')).'" />';
	?>
	
	<?php 
		$rss=get_bloginfo('rss2_url');
		if(get_option(RM_SHORT.'rss_url') and get_option(RM_SHORT.'rss_url')!="")
			$rss=stripslashes(get_option(RM_SHORT.'rss_url'));
	?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo $rss; ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url')?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url');?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>	
</head>

<body>

	<div id="wrapper">
	
		<div id="header<?php if($homepage) echo'_index';?>">
		
			<div id="logo">
			<?php
				$logo=get_bloginfo('template_directory')."/images/logo.png";
				if(get_option(RM_SHORT.'logo') and get_option(RM_SHORT.'logo')!="")
					$logo=stripslashes(get_option(RM_SHORT.'logo'));
			?>
				<a href="<?php bloginfo('url'); ?>"><img src="<?php echo $logo;?>" alt="<?php bloginfo('name'); ?>" /></a>
				
			</div>
			<!-- /#logo -->
			
			<div id="menu">
			
				<div id="right_bg"></div>
			
				<ul>	
				<?php rm_mainmenu(); ?>
				<?php if(get_option(RM_SHORT.'login_link')!="false"): ?>
					<li id="login_link"><a href="#login_wrapper">Login</a></li>
				<?php endif; ?>
				</ul>
				<!-- /#menu ul -->
			
			</div>
			<!-- /#menu -->
	<?php if($homepage!=true): ?>
			<div id="header_info">
			
				<div class="left">
				<?php if(get_option(RM_SHORT.'show_breadcrumbs')!='false'): ?>
					<?php rm_breadcrumbs(); ?>
				<?php endif; ?>
				
				</div>
				
				<div class="right">
				
				<?php if(get_option(RM_SHORT.'show_rss')!='false'): ?>
					<a href="<?php echo $rss; ?>" class="rss"><?php _e('Subscribe to RSS'); ?></a>
				<?php endif; ?>
				</div>
			
			</div>
			<!-- /#header_info -->
	<?php endif; ?>
		
		</div>
		<!-- /#header -->