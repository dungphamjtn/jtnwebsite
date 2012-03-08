<?php

class rm_options_page{

	//data members:
	var $options;
	var $file_name;
	var $page_title;
	
	//constructor:	
	function rm_options_page($info, $options){
	$this->info=$info;
	$this->file_name=$info['pagename']; //filename for the options page
	$this->page_title=$info['title']; //title, displayed near top of page
	$this->options=$options;
	
	add_action('admin_menu', array(&$this, 'rm_add_menu'));
	
	}
	
	//Add menu item
	function rm_add_menu(){

		if(!$this->info['sublevel']){
			add_menu_page(RM_THEME, RM_THEME, 'administrator', $this->file_name, array(&$this, 'rm_generate_page')); 
		}
		else{			
			add_submenu_page(RM_MAINMENU_NAME, $this->page_title, $this->page_title, 'administrator', $this->file_name, array(&$this, 'rm_generate_page')); 		
		}
	}
		
	//Generate functions page
	function rm_generate_page(){
		$this->save_options();

?>

	<form method="post" id="rm_options_form" class="rm_options_form">
	<input type="hidden" name="action" id="action" value="rm_save_options" />
		<div class="rm_section">	
			
			<div class="rm_title clearfix">
					<div class="rm_title_image clearfix">
						<img src="<?php echo RM_THEME_LOGO; ?>" alt="<?php echo RM_THEME; ?>" />
						
						<div class="rm_meta">

							<div>Version: <?php echo RM_THEME_VERSION; ?></div>
							<div><a href="<?php echo RM_THEME_DOCS; ?>" target="_blank">View docs</a></div>
						</div><!-- //rm_meta -->
					</div><!-- //rm_title_image -->
					
					
				
					<div class="rm_title_text">
						<h3><?php echo $this->page_title; ?></h3>
						<span class="submit">

							<input name="save" type="submit" value="Save changes" />
						</span>
					</div><!-- //rm_title_text -->
			</div><!-- //rm_title -->

			
			
			<div class="rm_options">

<?php
		foreach ($this->options as $value){
		
			switch ( $value['type'] ){
				
				case "text": $this->display_text($value); break;
				
				case "textarea": $this->display_textarea($value); break;
				
				case "image": $this->display_image($value); break;
				
				case "checkbox": $this->display_checkbox($value); break;
				
				case "checkbox-nav": $this->display_checkbox_nav($value); break;
				
				case "radio": $this->display_radio($value); break;
				
				case "select": $this->display_select($value); break;
				
			
			}
			
		}
?>
				<div class="rm_input rm_save clearfix">						
					<div class="label">Save Options</div>
					
					<div class="option_wrap">
						<div class="option_control">						
							<input type="submit" class="button" id="final_submit" name="final_submit" value="Save changes" />

	
						</div>
						<div class="description">Save the options</div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_upload -->


				
			</div><!-- //rm_options -->
			
		</div><!-- //rm_section -->
	</form><!--//save form-->
		
<?php
	}




	function display_text($value){
?>
				<div class="rm_input rm_text clearfix">						
					<div class="label"><?php echo $value['name']; ?></div>

					
					<div class="option_wrap">
						<div class="option_control">						
							<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if(get_option($value['id'])) echo stripslashes(get_option($value['id']));else echo $value['default']; ?>" />
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_text -->



<?php
	}





	function display_textarea($value){
?>
				<div class="rm_input rm_textarea clearfix">						
					<div class="label"><?php echo $value['name']; ?></div>
					
					<div class="option_wrap">

						<div class="option_control">
							<textarea rows="5" cols="25" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>"><?php
							if(get_option($value['id'])) 
								echo stripslashes($prefix.get_option($value['id'])) ;
							else 
								echo $value['default']; 
							?></textarea>
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- /option_wrap -->
				</div><!-- //rm_textarea -->

<?php
	}




	function display_image($value){
?>
				<div class="rm_input rm_image clearfix">						
					<div class="label"><?php echo $value['name']; ?></div>
					
					<div class="option_wrap">
						<div class="option_control">						
							<input type="text" value="<?php 
							if(get_option($value['id'])) echo stripslashes(get_option($value['id']));
						else 
							echo $value['default']; 
						?>" name="<?php echo $value['id']; ?>"/>
							<span id="<?php echo $value['id']; ?>" class="button upload rm_upload">Upload Image</span>
							<?php if(get_option($value['id'])) :?>
								<span class="button rm_remove" id="remove_<?php echo $value['id']; ?>">Remove Image</span>
							<?php endif; ?>
							
							<div class="rm_image_preview">
								<?php if(get_option($value['id'])):?>
								<img src="<?php echo get_option($value['id']); ?>" />
								<?php elseif($value['default'] != ""):?>
								<img src="<?php echo $value['default']; ?>" />
								<?php endif; ?>
							</div>

						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_upload -->
<?php
	}




	function display_checkbox($value){
?>
			
				<div class="rm_input rm_checkbox clearfix">						
					<div class="label"><?php echo $value['name']; ?></div>
					
					<div class="option_wrap">
						<div class="option_control">
						<?php
						$ctr=-1;
						foreach($value['options'] as $cb_option):
							$ctr++;
							$checked='';
							if(get_option($prefix.$value['id'][$ctr])){
								if(get_option($prefix.$value['id'][$ctr]) == 'true') $checked=' checked="checked"';
								else $checked='';
							}
							else{
								if($value['default'][$ctr]=="checked") $checked=' checked="checked"';
							}
					?>	
							<input type="checkbox" id="<?php echo $value['id'][$ctr]; ?>"<?php echo $checked; ?> name="<?php echo $value['id'][$ctr]; ?>"><label for="<?php echo $value['id'][$ctr]; ?>"><?php echo $value['options'][$ctr]; ?></label><br/>
					<?php
						endforeach;
					?>
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_checkbox -->
<?php
	}


	
	
	
	
		function display_checkbox_nav($value){
?>
			
				<div class="rm_input rm_checkbox_nav clearfix">						
					<div class="label"><?php echo $value['name']; ?></div>
					
					<div class="option_wrap">
						<div class="option_control">
						<?php
						$ctr=-1;
						foreach($value['options'] as $cb_option):
							$ctr++;
							$checked='';
							if(get_option($prefix.$value['id'][$ctr])){
								if(get_option($prefix.$value['id'][$ctr]) == 'true') $checked=' checked="checked"';
								else $checked='';
							}
							else{
								if($value['default'][$ctr]=="checked") $checked=' checked="checked"';
							}
							
							$clearfix='';
							
							if($ctr%3==0 and $ctr!=0)
								$clearfix= ' style="clear:both"'; 
								
							$last='';
							
							if(($ctr+1)%3==0 )
								$last=' last'; 
							
					?>	
							<div class="checkbox-nav<?php echo $last; ?>"<?php echo $clearfix; ?>>
								<input class="rm_super_check" type="checkbox" id="<?php echo $value['id'][$ctr]; ?>"<?php echo $checked; ?> name="<?php echo $value['id'][$ctr]; ?>"><label for="<?php echo $value['id'][$ctr]; ?>"><?php echo $value['options'][$ctr]; ?></label>
							</div>
					<?php
						endforeach;
					?>
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_checkbox -->
<?php
	}

	function display_radio($value){
?>
				<div class="rm_input rm_radio clearfix">		
					<div class="label"><?php echo $value['name']; ?></div>

					
					<div class="option_wrap">
						<div class="option_control">
						<?php
						$ctr=-1;
						if(get_option($value['id'])) $default=get_option($value['id']);
						else $default = $value['default'];
						foreach($value['options'] as $rd_option):
							$ctr++;
							$checked='';
							if($value['values'][$ctr]==$default) $checked=' checked="checked"';
							
					?>
							<input type="radio" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $value['values'][$ctr]; ?>"<?php echo $checked; ?>><label for="<?php echo $value['id']; ?>"><?php echo $value['options'][$ctr]; ?></label><br/>
					<?php
						endforeach;
					?>
							
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_radio -->
<?php
	}




	function display_select($value){
?>			
				<div class="rm_input rm_select clearfix">		
					<div class="label"><?php echo $value['name']; ?></div>

					
					<div class="option_wrap">
						<div class="option_control">						
							<select id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>">
						<?php
							foreach($value['options'] as $sel_opt):
								if(get_option($value['id']) == $sel_opt) $default=get_option($value['id']);
								else $default = $value['default'];
								
								$selected='';							
								if($sel_opt == $default) $selected=' selected="selected"';
						?>						
								<option <?php echo $selected;?>><?php echo $sel_opt; ?></option>						
						<?php
							endforeach;
						?>
							</select>
						</div>
						<div class="description"><?php echo $value['desc']; ?></div>

					</div><!-- //option_wrap -->
				</div><!-- //rm_select -->
<?php
	}
	
	
	


	function save_options(){
		$prefix=RM_SHORT .'_';

		if (isset($_POST['action']) && $_POST['action'] == 'rm_save_options' ) {
							foreach ($this->options as $value) {
								$the_type=$value['type'];		
									
									if($the_type=="checkbox" or $the_type=="checkbox-nav"){
										$ctr=0;
										
										foreach( $value['options'] as $cbopt):
											$curr_id=$value['id'][$ctr];
											
											if(isset($_POST[$curr_id]))
												update_option($curr_id, 'true');
											
											else
												update_option($curr_id, 'false');
												
										$ctr++;		
										endforeach;
									}
									
									if($the_type!="checkbox" and $the_type!="checkbox-nav"){	
										update_option($value['id'], $_POST[ $value['id'] ]);
									}	
							
							}
					echo '<div id="message" class="updated fade"><p><strong>'.RM_THEME.' settings saved.</strong></p></div>';
			
					}
			
			else if (isset($_POST['action']) && $_POST['action'] == 'rm_reset_options' ) { 
			
					global $wpdb; //Access to the Wordpress Database
					$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'mighty_%'";					
					$wpdb->query($query);
					echo '<div id="message" class="updated fade"><p><strong>All '.RM_THEME.' settings reset.</strong></p></div>';
					}


	}	
	
	
	
	
	
}//end class
?>