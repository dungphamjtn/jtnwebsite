<?php
class RM_Popular extends WP_Widget {

    function RM_Popular() {
		$widget_ops = array( 'classname' => 'RM_Popular', 'description' => 'A widget with a slider, showing popular posts') ;
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mighty-popular' );		
		$this->WP_Widget( 'mighty-popular', 'Mighty Popular', $widget_ops, $control_ops );
    }
	
	 function widget($args, $instance) {   
		global $wpdb;
        extract($args);        
        $title = esc_attr($instance['title']);
		$cols=intval($instance['cols']);
		$per_col=intval($instance['per_col']);
		$image=$instance['image'];
		
		$total_posts = ($cols * $per_col);
        
		$popular_posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0, $total_posts");
		$count=0;
		$the_total=count($popular_posts);
		
         echo $before_widget;
            echo $before_title;
                echo $title;
            echo $after_title;
?>
	<ul id="popular_posts">
	<?php
		for($i=1;$i<=$cols;$i++){
			if($count>=$the_total) break;
?>
		<li>						
			<ul class="popular_posts_content">
			
<?php		
			for($j=1;$j<=$per_col;$j++){
			if($count<$the_total)
				$post=$popular_posts[$count];
			else break;
			$count++;
			$data=get_post_meta( $post->ID, RM_THEME, true );
			$post_img=$data['image'];
			if($post_img=="" or !$post_img)
				$post_img=$image;
?>
			  <li>
								
				<img src="<?php echo build_image($post_img, 60, 60); ?>" width="60" height="60" alt="<?php echo $post->post_title; ?>" class="alignleft" />
					<a href="<?php echo get_permalink($post->ID); ?>" class="title"><?php echo $post->post_title; ?></a>
					<a href="<?php echo get_permalink($post->ID); ?>#comments"><?php echo $post->comment_count; ?>  comments</a>								
			  </li>
<?php
			
			}
?>
			</ul>
		</li>
<?php
		}
?>
	</ul>
	<ul id="pop_nav">					
				<li id="prev_pop"></li>
				<!-- /#prev_pop -->
					
				<li id="next_pop"></li>
				<!-- /#next_pop -->
	</ul>
	<!-- /#pop_nav -->
							
<?php
        echo $after_widget;
    }
	
	
 function form($instance) {
 $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'cols' => '2', 'per_col' => '4', 'image' => get_bloginfo('template_directory').'/widgets/placeholder.png' ));
        $title=esc_attr($instance['title']);
		$cols=$instance['cols'];
		$per_col=$instance['per_col'];
		$image=$instance['image'];
?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('cols'); ?>">
                Number of columns
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('cols'); ?>" name="<?php echo $this->get_field_name('cols'); ?>" type="text" value="<?php echo $cols; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('per_col'); ?>">
                Posts per column
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('per_col'); ?>" name="<?php echo $this->get_field_name('per_col'); ?>" type="text" value="<?php echo $per_col; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('image'); ?>">
                Default image, if post has no image
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
        </p>
<?php		
    }
	
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
        $instance['title']=strip_tags($new_instance['title']);
        $instance['cols']=$new_instance['cols'];
        $instance['per_col']=$new_instance['per_col'];
        $instance['image']=$new_instance['image'];
        return $instance;

    }

}

add_action('widgets_init', create_function('', 'return register_widget("RM_Popular");'));
?>