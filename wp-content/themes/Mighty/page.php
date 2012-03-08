<?php get_header(); ?>
		
		
<?php if(have_posts()) : while(have_posts()) : the_post();
	
	$data=get_post_meta( $post->ID, RM_THEME, true );
	$box=$data['showbox'];
			?>
				
				
		
		<?php
			if($box!='No') include(TEMPLATEPATH.'/includes/top-box.php');
		?>
		
		<div id="content">
		
			<div id="left">	
				<div class="content_item">
			<?php
				the_content();
				wp_link_pages(); 
				
	endwhile; endif;
				?>
				</div>
				<!-- /.content_item -->

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
