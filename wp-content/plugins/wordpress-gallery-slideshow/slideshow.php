<?php
/*
Plugin Name: WordPress Gallery Slideshow
Plugin URI: http://myplugins.org/plugins/2009-06-26/wordpress-gallery-slideshow.html
Description: <a title="NextGEN Gallery WordPress Plugin" href="http://alexrabe.boelinger.com/wordpress-plugins/nextgen-gallery/">NextGEN Gallery</a> another plugin for WordPress by <a title="Alex Rabe" href="http://alexrabe.boelinger.com/">Alex Rabe</a> was the inspiration for this small and simple plugin. I really must say he did and still does a great job with this plugin but sometimes such a huge plugin is simply too much. So I decided to develop a small plugin with less configuration settings and no extra tables for the WordPress Database. Simplicity should be the guide for the advanture of developing this plugin. As far as I can say I did the job well and it is just a Slideshow to set your images in the right context. Nothing more nothing less.
Version: 1.5.7
Author: MyPlugins.org
Author URI: http://myplugins.org/

    Copyright 2008 MyPlugins.org  (email : info@myplugins.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('TXTDOMAIN', 'wordpress-gallery-slideshow');
load_plugin_textdomain( TXTDOMAIN, 'wp-content/plugins/wordpress-gallery-slideshow/languages' );

if ( !class_exists( 'org_myplugins_mpf' ) ) {
	require_once( "org/myplugins/mpf/mpf.class.php" );
}
require_once( "org/myplugins/slideshow/slideshow.class.php" );

class slideshow_options extends org_myplugins_mpf {

	var $_customMenuIcon = 'R0lGODlhEAAgAOZ/AN3d3evv8vHz9+nw94jBWKSkpKjQg7m5ua291pqw0oC2po6Ojn28U6ysrO/v74eHh56654Om24e1t4qr3mxsbJrKZmlpabS0tJqv0pO10Xme15TCip6enoeNQ5aWlnp6emqYpO7z9+3x9+fu9l+Ri5ubm4GBgaqqqqioqGSXPYiIiJa146KiopSUlJGRkezy99ff6tKfVJHGXpKSkvH0+PD194uLi+bt94G+U3BwcKO206HMk/T19/X19fPz85+00/Ly8uzs7O3t7fT09PHx8ZmZmfDw8Hx8fNTd6by8vLe3t5+01KGhoaC00o6u4HeoTIe9bMnInHaCXu/096+GP4/FY47CcY200Hd3d3iqn+/z93igxKjSdHyweJu45evs7J6z0pm35MjIyKG96GeaQIKmx4eyb4e7YX+zoYq9ZYCDVpjJapp7NdGvbou7g32i2cXTr6DNfefv9ZfIY8LCwnura2STkWmemvP09J6z07KysrGxscLcv66ursvLy////yH5BAEAAH8ALAAAAAAQACAAAAf/gH+Cg4SFggBJen2LjIx7AIJJQUM9lZaVREIXgno+eyccDScoBSotRUZ6gn09egUNBUUeRSUqJQ6qf31DFxwnHi4LNh5HHLirPhclBS42Dw8zH0xCuX0+SixMLQ8mKgsPBdSrQAcNLQvBKibL4rpEB+U5ORQ5oSdB1UZHH1gUHh4fKFiwgE9QAyJ+EtJRUkBMQj9B9hg08rDiw4iRHPgY4qMjkI8fHWz6A8CPnj0oU6a8AMmQy5cwB8FA0CSBTZsYcmIAg0QQgi94eAgdKvRFAB2CmvhYcUXBDgMGNtzZouHFEkEJeKyQsINLhTlr3IDQMODqnwQ0wigwMEcGAQJWeki8GfADaw0vaOK4xcEACokIcuqenQJBwoYqfBl0ARFhhOAEISBkyGImzZk6drZMcIz1xRjJUjp0UFPGyebHIp6oZhMjBhUyKVJw/oNBBJ/bcKK0uX37Rh5BtXkL5+3bZwABNGhMEaClefOjgmD4+ZGnunXrOnrGNBQIADs=';
	var $_pluginImage = 'slideshow-image';

	function _pluginDescription() {
		_e( '<p>The WordPress Gallery Slideshow adds the JW Image Rotator to WordPress. You can use the new slideshow shortcode tag to display the slideshow: <code>[slide]</code>', TXTDOMAIN );
		echo( '<p align="center"><iframe width="230" height="120" src="' . plugins_url($path = $this->_pluginSubfolderName . '/org/myplugins/slideshow/donate.html') . '"></iframe></p>' );
		_e( '<p>Every donation is gratefully accepted and allows the continued work on free software.</p>', TXTDOMAIN );
	}
	
	function _AddAdministrationAjax() {
		if ( is_admin() ) {
			wp_enqueue_style( 'org_myplugins_mpf', plugins_url($path = $this->_pluginSubfolderName . '/org/myplugins/mpf/style.css') );
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'post' );
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'farbtastic' );
		}
	}
	
	function _pluginSettings() {
		?>
		<script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#colorpicker_back').farbtastic('#slideshow_backcolor');
                jQuery('#colorpicker_light').farbtastic('#slideshow_lightcolor');
                jQuery('#colorpicker_front').farbtastic('#slideshow_frontcolor');
                jQuery('#colorpicker_screen').farbtastic('#slideshow_screencolor');
                jQuery('#slideshow_backcolor').focus( function(){
                    jQuery('#colorpicker_back').show();
                });
                jQuery('#slideshow_backcolor').blur( function(){
                    jQuery('#colorpicker_back').hide();
                });
                jQuery('#slideshow_lightcolor').focus( function(){
                    jQuery('#colorpicker_light').show();
                });
                jQuery('#slideshow_lightcolor').blur( function(){
                    jQuery('#colorpicker_light').hide();
                });
                jQuery('#slideshow_frontcolor').focus( function(){
                    jQuery('#colorpicker_front').show();
                });
                jQuery('#slideshow_frontcolor').blur( function(){
                    jQuery('#colorpicker_front').hide();
                });
                jQuery('#slideshow_screencolor').focus( function(){
                    jQuery('#colorpicker_screen').show();
                });
                jQuery('#slideshow_screencolor').blur( function(){
                    jQuery('#colorpicker_screen').hide();
                });
            });
        </script>
        <fieldset>
        <legend><h4><?php _e( 'Dimension', TXTDOMAIN ); ?></h4></legend>
            <table width="450" border="0" cellpadding="0" cellspacing="0" class="form-table">
                <tr valign="top">
                  <td width="225"><?php $this->DisplayPluginOption( 'slideshow_width' ); ?><label for="slideshow_width"><? _e( 'Width', TXTDOMAIN ); ?></label></td>
                  <td width="225"><?php $this->DisplayPluginOption( 'slideshow_height' ); ?><label for="slideshow_height"><? _e( 'Height', TXTDOMAIN ); ?></label></td>
                </tr>
            </table>
        </fieldset>
        <br />					
        <br />
        <fieldset>
        <legend><h4><?php _e( 'Colors', TXTDOMAIN ); ?></h4></legend>
        <table width="450" border="0" cellpadding="0" cellspacing="0" class="form-table">
            <tr valign="top">
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_backcolor' ); ?><label for="slideshow_backcolor"><? _e( 'Backcolor', TXTDOMAIN ); ?></label><div id="colorpicker_back" style="background:#F9F9F9;position:absolute;display:none;"></div></td>
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_lightcolor' ); ?><label for="slideshow_lightcolor"><? _e( 'Lightcolor', TXTDOMAIN ); ?></label><div id="colorpicker_light" style="background:#F9F9F9;position:absolute;display:none;"></div></td>
            </tr>
            <tr valign="top">
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_frontcolor' ); ?><label for="slideshow_frontcolor"><? _e( 'Frontcolor', TXTDOMAIN ); ?></label><div id="colorpicker_front" style="background:#F9F9F9;position:absolute;display:none;"></div></td>
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_screencolor' ); ?><label for="slideshow_screencolor"><? _e( 'Screencolor', TXTDOMAIN ); ?></label><div id="colorpicker_screen" style="background:#F9F9F9;position:absolute;display:none;"></div></td>
            </tr>
        </table>
        </fieldset>
        <br />
        <br />
        <fieldset>
        <legend><h4><?php _e( 'Various', TXTDOMAIN ); ?></h4></legend>	
        <table width="450" border="0" cellpadding="0" cellspacing="0" class="form-table">
            <tr valign="top">
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_logo' ); ?><label for="slideshow_logo"><? _e( 'Watermark', TXTDOMAIN ); ?></label></td>
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_rotatetime' ); ?><label for="slideshow_rotatetime"><? _e( 'Rotatetime', TXTDOMAIN ); ?></label></td>
            </tr>
            <tr valign="top">
                <td width="225">
                    <?php $this->DisplayPluginOption( 'slideshow_shuffle' ); ?><label for="slideshow_shuffle"><? _e( 'Randomly choosen picture', TXTDOMAIN ); ?></label><br />
                    <?php $this->DisplayPluginOption( 'slideshow_overstretch' ); ?><label for="slideshow_overstretch"><? _e( 'Overstretch', TXTDOMAIN ); ?></label><br />
                    <?php $this->DisplayPluginOption( 'slideshow_shownavigation' ); ?><label for="slideshow_shownavigation"><? _e( 'Show navigation', TXTDOMAIN ); ?></label><br />
                    <?php $this->DisplayPluginOption( 'slideshow_usefullscreen' ); ?><label for="slideshow_usefullscreen"><? _e( 'Enable fullscreen mode', TXTDOMAIN ); ?></label><br />
                    <?php $this->DisplayPluginOption( 'slideshow_autostart' ); ?><label for="slideshow_autostart"><? _e( 'Start slideshow automatically', TXTDOMAIN ); ?></label><br />
                </td>
                <td width="225"><?php $this->DisplayPluginOption( 'slideshow_transition' ); ?><label for="slideshow_transition"><? _e( 'Transition', TXTDOMAIN ); ?></label></td>
            </tr>
        </table>
        </fieldset>
		<?php
	}
}

$slideshowOptions = new slideshow_options();
$slideshow = new wordpress_gallery_slideshow();

if( isset($_GET[$slideshowOptions->_pluginImage]) && !empty($_GET[$slideshowOptions->_pluginImage])) {
	$slideshowOptions->getMenuIcon($_GET[$slideshowOptions->_pluginImage]);
}

$slideshowOptions->Initialize(	'WordPress Gallery Slideshow',
								'1.5.5',
								'wordpress-gallery-slideshow',
								'slideshow',
								'http://thomas.stachl.me/2008/11/29/tutorials/wordpress-gallery-slideshow/',
								'http://thomas.stachl.me/2008/11/29/tutorials/wordpress-gallery-slideshow/#comments',
								'http://wordpress.org/extend/plugins/wordpress-gallery-slideshow/',
								'http://thomas.stachl.me/plugins/',
								'http://thomas.stachl.me/kontakt/' );

$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_height',
	                        	'260',
	                        	__( 'That defines the standard height of the player.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_width',
	                        	'320',
	                        	__( 'That defines the standard width of the player.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_backcolor',
	                        	'#FFFFFF',
	                        	__( 'Hex code of the standard background color. That means the background color of the buttons.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_frontcolor',
	                        	'#000000',
	                        	__( 'Hex code of the standard front color. That means the color of the buttons.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_lightcolor',
	                        	'#000000',
	                        	__( 'Hex code of the standard light color. That means the color when you hover buttons.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'slideshow_screencolor',
	                        	'#000000',
	                        	__( 'Hex code of the standard screen color. That means the background color of the player.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
								'slideshow_logo',
								'',
								__( 'Here you can set a watermark logo for the player. Insert the URL of the logo.', TXTDOMAIN ) );
$comboboxArray = array( 'random' => __( 'Random', TXTDOMAIN ), 'fade' => __( 'Fade', TXTDOMAIN ), 'bgfade' => __( 'Background Fade', TXTDOMAIN ), 'blocks' => __( 'Blocks', TXTDOMAIN ),'bubbles' => __( 'Bubbles', TXTDOMAIN ), 'circles' => __( 'Circles', TXTDOMAIN ), 'flash' => __( 'Flash', TXTDOMAIN ), 'fluids' => __( 'Fluids', TXTDOMAIN ), 'lines' => __( 'Lines', TXTDOMAIN ), 'slowfade' => __( 'Slowfade', TXTDOMAIN ));
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'slideshow_transition',
								'random',
								__( 'You may set a standard transition for the slideshows.', TXTDOMAIN ),
								$comboboxArray );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
								'slideshow_rotatetime',
								'5',
								__( 'Set the standard rotatetime.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'slideshow_shuffle',
								$slideshowOptions->CHECKBOX_UNCHECKED,
								__( 'Shuffle images.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'slideshow_overstretch',
								$slideshowOptions->CHECKBOX_UNCHECKED,
								__( 'Overstretch images.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'slideshow_shownavigation',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Show the navigation buttons.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'slideshow_usefullscreen',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Allow fullscreen mode.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'slideshow_autostart',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Autostart the slideshow.', TXTDOMAIN ) );

$slideshowOptions->RegisterOptions( __FILE__ );

$slideshowOptions->AddAdministrationPageBlock(	'block-description',
	                                        	__( 'Plugin Description', TXTDOMAIN ),
	                                        	$slideshowOptions->CONTENT_BLOCK_TYPE_SIDEBAR,
	                                        	array($slideshowOptions, '_pluginDescription'),
												1 );
$slideshowOptions->AddAdministrationPageBlock(	'block-settings',
	                                        	__( 'General Options', TXTDOMAIN ),
	                                        	$slideshowOptions->CONTENT_BLOCK_TYPE_MAIN,
	                                        	array($slideshowOptions, '_pluginSettings'),
												1 );
$slideshowOptions->RegisterAdministrationPage(	$slideshowOptions->PARENT_MENU_MEDIA,
	                                         	$slideshowOptions->ACCESS_LEVEL_ADMINISTRATOR,
	                                         	__( 'Slideshow', TXTDOMAIN ),
	                                         	__( 'Slideshow Options Page', TXTDOMAIN ),
	                                         	'slideshow',
											 	'slideshow.gif' );

add_action( 'init', array($slideshowOptions, '_AddAdministrationAjax' ) );
add_action( 'admin_head' , array($slideshowOptions, 'AddHoverFunctionality')	);
add_action( 'wp_head', array($slideshow, 'addToHeader'), 1);

add_shortcode('gallery', array($slideshow, 'wp_gallery_slideshow_shortcut'));
add_shortcode('slide', array($slideshow, 'wp_gallery_slideshow_own_shortcut'));
function show_slideshow($attr) {
	global $slideshow;
	$result = '';
	$attr .= "&slide=1";
	$attributes = explode('|', $attr);
		foreach($attributes as $attribute){
			if($attribute){
				// find the position of the first '='
				$i = strpos($attribute, '=');
				// if not a valid format ('key=value) we ignore it
				if ($i){
					$key = substr($attribute, 0, $i);
					$val = substr($attribute, $i+1); 
					$result[$key]=$val;
				}
			}
		}
	echo $slideshow->wp_gallery_slideshow_shortcut($result, 1);
}

?>