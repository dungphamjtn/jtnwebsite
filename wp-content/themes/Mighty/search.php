<?php get_header(); ?>
	
		<?php
			if(get_option(RM_SHORT.'blog_box') != 'false') 
				include(TEMPLATEPATH.'/includes/top-box.php');
		?>
		
		<div id="content">
		
			<div id="left">	
				
				<?php	

				if(have_posts()) : while(have_posts()) : the_post();
						include(TEMPLATEPATH.'/includes/post-w-comments.php');
					  endwhile; endif;
				?>
				<ul id="pagination">
		
			<?php
				include(TEMPLATEPATH . '/includes/wp-pagenavi.php');
					if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				?>
		</ul>
			</div>
			<!-- /#content #left - left side of main content -->
			
			<div id="right">
				<?php 
				include(TEMPLATEPATH . '/includes/sidebar-blog.php');
				?>
			</div>
			<!-- /#content #right - right side of main content or sidebar -->
		
		</div>
		<!-- /#content -->
		
	</div>
	<!-- /#wrapper -->
	
<?php get_footer(); ?>
