<?php get_header(); ?>
		
				
				
		
		<div id="content">
		
			<div id="left">	
				<div class="content_item">
			<?php
				echo stripslashes(get_option(RM_SHORT.'404'));
			?>
				</div>
				<!-- /.content_item -->

				<div class="content_item" id="comments">	
		
						<?php comments_template(); ?>
		 
				</div>
				<!--/ comments -->
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
