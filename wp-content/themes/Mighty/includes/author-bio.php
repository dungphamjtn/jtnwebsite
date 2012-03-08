<div class="content_item">
<?php
		$id=get_the_author_meta('ID');
		$numposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = $id AND post_type = 'post' AND post_status = 'publish'");
		$num=count($numposts);
?>

				
					<h3>About the Author</h3>
					
					<div class="about_author">

					
						<?php echo get_avatar( get_the_author_meta('email') , '80' ); ?>
						
						
						<p><a href="" class="author_link"><?php the_author(); ?></a> has written <?php echo $num; ?> articles for <?php bloginfo('name'); ?>.</p>
						
						<span><?php the_author_meta('description'); ?></span>
					
					</div>
				
				</div>
				<!-- /.content_item -->