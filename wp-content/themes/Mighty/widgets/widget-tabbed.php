<?php
class RM_Tabbed extends WP_Widget {

    function RM_Tabbed() {
		$widget_ops = array( 'classname' => 'RM_Tabbed', 'description' => 'A tabbed widget showing categories, pages and archives.' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mighty-tabbed' );		
		$this->WP_Widget( 'mighty-tabbed', 'Mighty Tabbed', $widget_ops, $control_ops );
    }
    function widget($args, $instance) {        
        extract($args);        
        $title = esc_attr($instance['title']);
        
         echo $before_widget;
            echo $before_title;
                echo $title;
            echo $after_title;
?>
	<ul id="tabs">					
		<li>Categories</li>
		<li>Archives</li>
		<li>Pages</li>					
	</ul>
	
	<ul id="ctabs">					
		<li>	
			<ul class="ctabs_content">
					<?php wp_list_categories('title_li=&depth=1'); ?>
			</ul>
			<!-- /.ctabs_content -->
		</li>			
		<li>	
			<ul class="ctabs_content">
					<?php wp_get_archives('type=monthly&show_post_count=0'); ?> 
			</ul>
			<!-- /.ctabs_content -->
		</li>
		<li>	
			<ul class="ctabs_content">
					<?php wp_list_pages('title_li=&depth=1'); ?>
			</ul>
			<!-- /.ctabs_content -->
		</li>
</ul>
<?php        
        echo $after_widget;
			
			
			
    }
    function form($instance) {
        $title = esc_attr($instance['title']);    
?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
               Title
            </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
<?php		
    }
	
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
        $instance['title']=strip_tags($new_instance['title']);
        return $instance;

    }

}

add_action('widgets_init', create_function('', 'return register_widget("RM_Tabbed");'));
?>