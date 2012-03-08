<?php get_header(); ?>
		
		
		<?php
			if(get_option(RM_SHORT.'blog_box') != 'false') 
				include(TEMPLATEPATH.'/includes/top-box.php');
		?>
		
		<div id="content">
		
			<div id="left">	
				
				<?php	
					$args=array();
					$exclude=rm_build_excludecat('rm_cat');
					if(!empty($exclude)){
						$args['category__not_in']=$exclude;
						query_posts($args);
					}

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
				<?php get_sidebar(); ?>
			</div>
			<!-- /#content #right - right side of main content or sidebar -->
		
		</div>
		<!-- /#content -->
		
	</div>
	<!-- /#wrapper -->
	
<?php get_footer(); ?>
