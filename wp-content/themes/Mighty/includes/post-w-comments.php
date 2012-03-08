				<div class="content_item">
				
					<h1 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					
					<h5>By <?php the_author(); ?> in : <?php the_category(', '); ?> // <?php the_time('M j Y'); ?></h5>		
					<a href="<?php the_permalink(); ?>#comments" class="comments_balloon"><?php comments_number('0', '1', '%'); ?></a>

					
					<?php
						$data=get_post_meta( $post->ID, RM_THEME, true );
						$post_img=$data['image'];
						$post_type=$data['posttype'];
						
						if($post_type=="Fullsize"):						
					?>	
					<p><img src="<?php echo build_image($post_img, 612, 158); ?>" width="612" height="158" alt="<?php the_title(); ?>" /></p>
					<?php
						elseif($post_type=="Thumbnail"):
					?>
					<p><img src="<?php echo build_image($post_img, 202, 90); ?>" width="202" height="90" alt="<?php the_title(); ?>" class="alignleft"/></p>
					<?php endif; ?>
					
					
					<?php the_excerpt(); ?>
					
					<a href="<?php the_permalink(); ?>" class="read_more">Continue Reading</a>
					
				</div>
				<!-- /.content_item -->