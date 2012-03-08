<?php get_header(); ?>
<?php
	if(get_option(RM_SHORT.'blog_box') != 'false') 
		include(TEMPLATEPATH.'/includes/top-box.php');
?>
<div id="content">
		
			<div id="left">	
				<div class="content_item">
		
			<?php if(have_posts()) : while(have_posts()) : the_post();
			?>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<a href="#comments" class="comments_balloon"><?php comments_number('0', '1', '%'); ?></a>
			<h5>By <?php the_author(); ?> in : <?php the_category(', '); ?> // <?php the_time('M j Y'); ?></h5>		
					

			<?php
				the_content();
				wp_link_pages(); 
			?>
				</div>
				<!-- /.content_item -->
			<?php if(get_option(RM_SHORT.'author_bio')!='false')
				include (TEMPLATEPATH.'/includes/author-bio.php');
			?>
				
				<div class="content_item" id="comments">	
		
						<?php comments_template(); ?>
		 
				</div><!--/ comments -->
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