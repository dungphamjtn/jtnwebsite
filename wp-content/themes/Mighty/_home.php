<?php
/*
Template Name: Home
*/
global $homepage;
$homepage=true;

get_header(); ?>
		
		<div class="clear">
		
			<div id="slider">
			
				<ul>
			<?php if(have_posts()) : while(have_posts()) : the_post();
					the_content();		
				   endwhile; endif;
			?>
				
				
				</ul>
			<!-- /#slider ul -->
			
			</div>
			<!-- /#slider -->
		
			<div id="slider_selector">
				
				<div id="slider_selector_right"></div>
				<!-- /#slider_selector_right - right background of selector -->
			
			</div>
			<!-- /#slider_selector -->
			
		</div>
		<!-- /.clear -->
		<?php include(TEMPLATEPATH.'/includes/top-box.php'); ?>
		
		<div id="content">
		
			<div id="left">
		<?php
			$args=array();
			$num_posts=3;
			if(get_option(RM_SHORT.'home_posts') and get_option(RM_SHORT.'home_posts')!="")
				$num_posts=get_option(RM_SHORT.'home_posts');
			$args['posts_per_page']=$num_posts;
			$exclude=rm_build_excludecat('rm_cat');
			if(!empty($exclude))
				$args['category__not_in']=$exclude;
			$the_query=new WP_Query($args);
			if($the_query->have_posts()) : while($the_query->have_posts()) : $the_query->the_post();
		?>
			
				<?php include(TEMPLATEPATH.'/includes/post-w-comments.php'); ?>
			
		<?php
			endwhile; endif;
		?>
			</div>
			<!-- /#content #left - left side of main content -->
			
			<div id="right">
				<?php get_sidebar(); ?>
			</div>
			<!-- /#content #right - right side of main content or sidebar -->
		
		</div>
		<!-- /#content -->
		
	</div>
	<!-- /#wrapper -->
	
<?php get_footer(); ?>
