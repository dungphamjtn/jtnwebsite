<?php
/*
Template Name: Full Width
*/
get_header();
?>
<?php if(have_posts()) : while(have_posts()) : the_post();
	
	$data_p=get_post_meta( $post->ID, RM_THEME, true );
	$box=$data_p['showbox'];
?>	
		
		<?php
			if($box!='No') include(TEMPLATEPATH.'/includes/top-box.php');
		?>
		
		<div id="content">
			<?php the_content();
				  wp_link_pages();
			?>
		</div>
		<!-- /#content -->
		
	</div>
	<!-- /#wrapper -->
<?php endwhile; endif; ?>
			
				
				
	
<?php get_footer(); ?>



