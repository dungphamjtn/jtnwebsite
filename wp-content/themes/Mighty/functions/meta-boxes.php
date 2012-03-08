<?php
$key = RM_THEME;

$categories = get_categories('hide_empty=0&orderby=name');
$wp_cats = array();
foreach ($categories as $category_list ) {
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($wp_cats, "Choose a category"); 

$meta_boxes_page = array(

"topbox" => array(
"name" => "showbox",
"title" => "Show top box?",
"type" => "select",
"options" => array("Yes", "No"),
"description" => "Select whether or not to display the box at the top of this page. Will NOT be dsplayed for blog pages."),

"portfolio" => array(
"name" => "portfolio",
"title" => "Portfolio category",
"type" => "select",
"options" => $wp_cats,
"description" => "If this page is a portfolio page, choose which category posts for this page should be drawn from")


);

$meta_boxes_post = array(

"image" => array(
"name" => "image",
"title" => "Post image",
"type" => "text",
"description" => "Enter the URL for a post image. Will be resized if you chose to enable auto-resizing. <strong>Portfolio posts must have this image</strong>"),

"posttype" => array(
"name" => "posttype",
"title" => "Post type",
"type" => "select",
"options" => array("Fullsize", "Thumbnail", "No-Image"),
"description" => "Select whether the post is to use a fullsize image, a smaller thumbnail image or no image at all. If you do not add an image, no-image is automatically chosen.")

);




//ADD META BOX
function create_meta_box() {
	global $key;

	if( function_exists( 'add_meta_box' ) ) {
	add_meta_box( 'meta-boxes-post', ucfirst( $key ) . ' Options', 'display_meta_box_post', 'post', 'normal', 'high' );
	add_meta_box( 'meta-boxes-page', ucfirst( $key ) . ' Options', 'display_meta_box_page', 'page', 'normal', 'high' ); 
	}

}
 
function display_meta_box_post(){
	global $meta_boxes_post;
	display_meta_box($meta_boxes_post);
}
function display_meta_box_page(){
	global $meta_boxes_page;
	display_meta_box($meta_boxes_page);
}



//DISPLAY META BOX
function display_meta_box($meta_boxes) {
global $post, $key;
?>

<div class="form-wrap">

<?php
wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true );

foreach($meta_boxes as $meta_box) {
$data = get_post_meta($post->ID, $key, true);
?>

<div class="form-field form-required">
<label for="<?php echo $meta_box[ 'name' ]; ?>"><strong><?php echo $meta_box[ 'title' ]; ?></strong></label>

<?php if($meta_box['type']=="text") :?>
<input type="text" name="<?php echo $meta_box[ 'name' ]; ?>" value="<?php echo htmlspecialchars( $data[$meta_box['name']] ); ?>" />
<?php endif; ?>

<?php if($meta_box['type']=="textarea") :?>
<textarea name="<?php echo $meta_box[ 'name' ]; ?>" ><?php echo htmlspecialchars( $data[$meta_box['name']] ); ?></textarea>
<?php endif; ?>

<?php if($meta_box['type'] == "select") : ?>
   
	<select name="<?php echo $meta_box['name']; ?>" id="<?php echo $meta_box['name']; ?>" >  
   <?php foreach ($meta_box['options'] as $option) {  
        echo'<option';  
      
       if ( $data[$meta_box['name']] == $option) {  
           echo ' selected="selected"';  
        } elseif ($option == $value['std']) {  
           echo ' selected="selected"';  
       }  
     
       echo '>'.$option.'</option>';  
   }  
   echo '</select>';  
   
 endif; ?>

<p><?php echo $meta_box[ 'description' ]; ?></p>
</div>


<?php } ?>

</div>
<?php
}





//SAVE META BOX
function save_meta_box( $post_id ) {
global $post, $meta_boxes_post, $meta_boxes_page, $key;

	foreach( $meta_boxes_post as $meta_box ) {
		$data[ $meta_box[ 'name' ] ] = $_POST[ $meta_box[ 'name' ] ];
	}
		
	foreach($meta_boxes_page as $meta_box) {
		$data[ $meta_box[ 'name' ] ] = $_POST[ $meta_box[ 'name' ] ];
	}


	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) )
	return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ))
	return $post_id;

	update_post_meta( $post_id, $key, $data );
}


add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_meta_box');
?>