<?php
/*
Template Name: Portfolio
*/
get_header();
?>
<?php if(have_posts()) : while(have_posts()) : the_post();
	
	$data_p=get_post_meta( $post->ID, RM_THEME, true );
	$box=$data_p['showbox'];
	$category=$data_p['portfolio'];
?>	
		
		<?php
			if($box!='No') include(TEMPLATEPATH.'/includes/top-box.php');
		?>
		
		<div id="content">
			<?php the_content(); ?>
<?php endwhile; endif; ?>
			
				<ul id="portfolio">
					<li>				
						<ul>

				<?php
				$ctr=0;
				$posts_per_page=intval(get_option(RM_SHORT.'portfolio_page'));
				if(!$posts_per_page or $posts_per_page==0)
					$posts_per_page=6;
				query_posts('category_name='.$category.'&paged='.$paged.'&posts_per_page='.$posts_per_page);
				if(have_posts()) : while(have_posts()) : the_post(); 
						$ctr++;
						$data=get_post_meta( $post->ID, RM_THEME, true );
						$post_img=$data['image'];
						$the_class='';
						if($ctr%3==0)
							$the_class='class="no_margin_r"';
				?>
								<li <?php echo $the_class; ?>>
									<a href="<?php the_permalink(); ?>">
										<img src="<?php echo build_image($post_img, 292, 146); ?>" width="292" height="146" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
									</a>	
								</li>
				<?php endwhile; endif; ?>
						</ul>

					</li>	
			
				</ul>
				
				<ul id="pagination" style="margin: 10px 0 0 0;">
				<?php
					include(TEMPLATEPATH . '/includes/wp-pagenavi.php');
					if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				?>
				</ul>
				
		</div>
		<!-- /#content -->
		
	</div>
	<!-- /#wrapper -->
	
<?php get_footer(); ?>



