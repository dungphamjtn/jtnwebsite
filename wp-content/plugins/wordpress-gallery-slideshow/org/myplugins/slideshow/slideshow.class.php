<?php

class wordpress_gallery_slideshow {
	var $gid = 0;
	
	function wp_gallery_slideshow_shortcut($attr, $type) {
		global $post;
		global $wp_version;
		global $slideshowOptions;
		
		$attributes = $attr;
		
		if ( $type )
			$slide = 1;
		else
			$slide = 0;
			
		$height = get_option( 'slideshow_height' );
		$width = get_option( 'slideshow_width' );
		$backcolor = get_option( 'slideshow_backcolor' );
		$frontcolor = get_option( 'slideshow_frontcolor' );
		$lightcolor = get_option( 'slideshow_lightcolor' );
		$screencolor = get_option( 'slideshow_screencolor' );
		$logo = get_option( 'slideshow_logo' );
		$transition = get_option( 'slideshow_transition' );
		$rotatetime = get_option( 'slideshow_rotatetime' );
		$shuffle = get_option( 'slideshow_shuffle' );
		$overstretch = get_option( 'slideshow_overstretch' );
		$shownavigation = get_option( 'slideshow_shownavigation' );
		$usefullscreen = get_option( 'slideshow_usefullscreen' );
		$autostart = get_option( 'slideshow_autostart' );
	
		extract(shortcode_atts(array(
			'id'         		=> $post->ID,
			'slide'		 		=> $slide,
			'height'	 		=> $height,
			'width'		 		=> $width,
			'backcolor'	 		=> $backcolor,
			'frontcolor' 		=> $frontcolor,
			'lightcolor' 		=> $lightcolor,
			'screencolor'		=> $screencolor,
			'screenalpha'		=> 'false',
			'logo'		 		=> $logo,
			'transition' 		=> $transition,
			'rotatetime' 		=> $rotatetime,
			'shuffle'	 		=> $shuffle,
			'overstretch'		=> $overstretch,
			'shownavigation'	=> $shownavigation,
			'usefullscreen'		=> $usefullscreen,
			'autostart'			=> $autostart,
			'flickr'			=> '',
			'picasa'			=> '',
			'audio'				=> '',
		), $attr));

		$id = intval($id);
		
		$backcolor = substr($backcolor, 1);
		$frontcolor = substr($frontcolor, 1);
		$lightcolor = substr($lightcolor, 1);
		$screencolor = substr($screencolor, 1);
		
		$shuffle = $this->checkOn( $shuffle );
		$overstretch = $this->checkOn( $overstretch );
		$shownavigation = $this->checkOn( $shownavigation );
		$usefullscreen = $this->checkOn( $usefullscreen );
		$autostart = $this->checkOn( $autostart );
		
		if ( function_exists( 'gallery_shortcode' ) ) {
			$gallery = gallery_shortcode( $attributes );
		}
		
		$attr = "attr=";
		if ( $flickr ) {
			$flickr = $this->encode($flickr);
			$attr .= "flickr^$flickr#";
		}
		if ( $picasa ) {
			$picasa = $this->encode($picasa);
			$attr .= "picasa^$picasa#";
		}
		if ( $order )
			$attr .= "order^$order#";
		if ( $id )
			$attr .= "id^$id#";
		
		if (!$audio == '')
			$audio = "flashvars.audio = '".urlencode($audio)."';";
		
		$this->gid++;
		
		if ( $slide )
			$output = "
				<script type='text/javascript'>
					var flashvars = {};
					flashvars.file = '" . plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/org/myplugins/slideshow/getxml.php') . "?$attr';
					$audio
					flashvars.rotatetime = '$rotatetime';
					flashvars.autostart = '$autostart';
					flashvars.backcolor = '0x$backcolor';
					flashvars.frontcolor = '0x$frontcolor';
					flashvars.lightcolor = '0x$lightcolor';
					flashvars.screencolor = '0x$screencolor';
					flashvars.screenalpha = '$screenalpha'
					flashvars.logo = '$logo';
					flashvars.transition = '$transition';
					flashvars.shuffle = '$shuffle';
					flashvars.overstretch = '$overstretch';
					flashvars.shownavigation = '$shownavigation';
					flashvars.height = '$height';
					flashvars.width = '$width';
					var params = {};
					params.allowFullScreen = '$usefullscreen';
					params.wmode = 'transparent';
					params.menu = 'false';
					var attributes = {};
					swfobject.embedSWF ('" . plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/org/myplugins/slideshow/imagerotator.swf') . "', 'slideshow_" . $this->gid . "', '$width', '$height', '9.0.0', false, flashvars, params, attributes);
				</script>
				<div class='slideshow' id='slideshow_" . $this->gid . "'>
					$gallery
				</div>
				\n";
		else
			$output = "	<div class='gallery' id='gallery_" . $this->gid . "'>
							$gallery
						</div>
						\n";
		
		return $output;
	}
	
	function checkOn( $string ) {
		
		if ( $string == "on" || $string == "true" ) {
			return "true";
		} else {
			return "false";
		}
		
	}
	
	function addToHeader() {
		global $slideshowOptions;
		wp_enqueue_script( 'swfobject', plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/org/myplugins/slideshow/swfobject/swfobject.js'), false, '2.1' );
	}
	
	function encode($x) {
	    return base64_encode(rawurlencode($x));
	}

	
	function wp_gallery_slideshow_own_shortcut($attr) {
		return $this->wp_gallery_slideshow_shortcut($attr, 1);
	}
	
}

?>