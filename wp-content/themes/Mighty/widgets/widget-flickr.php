<?php
class RM_Flickr extends WP_Widget {

    function RM_Flickr() {
		$widget_ops = array( 'classname' => 'RM_Flickr', 'description' => 'Fetches Flickr images. Meant to be used in footer, column 4.' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mighty-flickr' );		
		$this->WP_Widget( 'mighty-flickr', 'Mighty Flickr', $widget_ops, $control_ops );
    }
    function widget($args, $instance) {        
        extract($args);        
        $title = esc_attr($instance['title']);
        $flickr_id= $instance['flickr_id'];
        $num_images= intval($instance['num_images']);
		
		if($num_images==0 or !$num_images)
			$num_images=8;
        
         echo $before_widget;
            echo $before_title;
                echo $title;
            echo $after_title;
			
		require_once('simplepie.inc');
		require_once('flickr-functions.php');
		$feed = new Simplepie('http://api.flickr.com/services/feeds/photos_public.gne?id='.$flickr_id.'&lang=en-us&format=rss_200');
		$feed->handle_content_type();
		
		
		
		$num=$num_images;
		$counter=-1;
		
		foreach ($feed->get_items() as $item): 
			$counter++;
			if($counter==$num)
				break;						

				if ($enclosure = $item->get_enclosure()) {
					$img = image_from_description($item->get_description());
					$full_url = trim(select_image($img, 3));
					$thumb_url = trim(select_image($img, 0));
					echo '<a href="' . $full_url . '" title="' . $enclosure->get_title() . '" class="lightbox"><img src="'. $thumb_url .'" alt="" width="75" height="75" /></a>';
				}
		endforeach; 
			
			
        echo $after_widget;
			
			
			
    }
    function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'flickr_id' => '', 'num_images' => '8' ));
        $title = esc_attr($instance['title']);    
        $flickr_id= $instance['flickr_id'];  
        $num_images= $instance['num_images'];
?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
               Title
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('flickr_id'); ?>">
               Flickr ID (Use <a href='http://idgettr.com/'>idGettr</a>)
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $flickr_id; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('num_images'); ?>">
               Number of images (Multiple of 3 preferable)
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('num_images'); ?>" name="<?php echo $this->get_field_name('num_images'); ?>" type="text" value="<?php echo $num_images; ?>" />
        </p>
<?php		
    }
	
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
        $instance['title']=strip_tags($new_instance['title']);
        $instance['flickr_id']= $new_instance['flickr_id'];
        $instance['num_images']= $new_instance['num_images'];
        return $instance;

    }
	

}

add_action('widgets_init', create_function('', 'return register_widget("RM_Flickr");'));
?>