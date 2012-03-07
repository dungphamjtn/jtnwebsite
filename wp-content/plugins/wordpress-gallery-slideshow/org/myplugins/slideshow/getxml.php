<?php
	header("content-type:text/xml;charset=utf-8");
	
	$root = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
	error_reporting(E_ALL);
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');
	} else {
		if (!file_exists($root.'/wp-config.php'))
			die;
		require_once($root.'/wp-config.php');
	}
	
	function get_out_now() { exit; }
	function decode($x) {
	    return html_entity_decode(rawurldecode(base64_decode($x)));
	} 
	add_action('shutdown', 'get_out_now', -1);
	
	global $wpdb;
	
	$siteurl	 = get_option ('siteurl');
	
	$attr = attribute_escape($_GET['attr']);
	
	$attributes = explode('#', $attr);
		foreach($attributes as $attribute){
			if($attribute){
				$i = strpos($attribute, '^');
				if ($i){
					$key = substr($attribute, 0, $i);
					$val = substr($attribute, $i+1); 
					$result[$key]=$val;
				}
			}
		}
	if ($result['id'])
		$galleryID = (int) attribute_escape($result['id']);
		
	if ($result['flickr']) {
		$flickr = attribute_escape($result['flickr']);
		$flickr = decode($flickr);
	}
	
	if ($result['picasa']) {
		$picasa = attribute_escape($result['picasa']);
		$picasa = decode($picasa);
	}
	
	$order = 'ASC';
	if ($result['order'])
		$order = $result['order'];
	
	echo "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
	echo "	<trackList>\n";
	
	
	if ($galleryID) {
		$thepictures = $wpdb->get_results("SELECT $wpdb->posts.post_title, $wpdb->posts.post_excerpt, $wpdb->posts.post_content, $wpdb->posts.post_name, $wpdb->posts.guid, $wpdb->posts.post_mime_type FROM $wpdb->posts WHERE $wpdb->posts.post_parent = $galleryID ORDER BY $wpdb->posts.menu_order $order;");	
		foreach ($thepictures as $picture) {
			if ( strpos( $picture->post_mime_type, 'image' ) !== false ) {
				echo "		<track>\n";
				if (!empty($picture->post_content))	
				echo "			<title>".substr(strip_tags(stripslashes($picture->post_content)),0,50)."</title>\n";
				else if (!empty($picture->post_excerpt))	
				echo "			<title>".substr(stripslashes($picture->post_excerpt),0,50)."</title>\n";
				else
				echo "			<title>".$picture->post_name."</title>\n";
				echo "			<location>".$picture->guid."</location>\n";
				echo "		</track>\n";
			}
		}
	
	} elseif ($flickr) {
		if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
			include_once (ABSPATH . WPINC . '/rss.php');
			error_reporting(E_ALL);
		}
	
		$rss = @ fetch_rss($flickr);
		
		if ($rss) {
	    	$imgurl = "";
			$items = array_slice($rss->items, 0, 15);
			
			if ($rss->feed_type == "RSS") {
				$content = "summary";
			} else {
				$content = "atom_content";
			}
			
	    	foreach ( $items as $item ) {
				echo "		<track>\n";
				if ( $item['title'] )
					echo "			<title>".substr(strip_tags(stripslashes($item['title'])),0,50)."</title>\n";
				
				preg_match('<img src="([^"]*)" [^/]*/>', $item[$content],$imgUrlMatches);
				$imgurl = $imgUrlMatches[1];
				$imgurl = str_replace("_m.jpg", ".jpg", $imgurl);
	
				echo "			<location>".$imgurl."</location>\n";
				echo "		</track>\n";
			
			}
	     }
	} elseif ($picasa) {
		if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
			include_once (ABSPATH . WPINC . '/rss.php');
			error_reporting(E_ALL);
		}
		$rss = @ fetch_rss($picasa);
		if ($rss) {
			$imgurl = "";
			$items = array_slice($rss->items, 0, 15);
			
			if ($rss->feed_type == "RSS") {
				$content = "summary";
			} else {
				$content = "summary";
			}
			
			foreach ( $items as $item ) {
				echo "		<track>\n";
				if ( $item['title'] )
					echo "			<title>".substr(strip_tags(stripslashes($item['title'])),0,50)."</title>\n";
	        	preg_match('#src="([^"]+)#', $item[$content], $result);
	            
				$url = $result[0];
				$urlprefix = substr($url, 0, strrpos($url, "/")-1);
				$urlprefix = substr($urlprefix, 0, strrpos($urlprefix, "/"));
				$urlprefix = substr($urlprefix, 5);
				$imagename = substr($url, strrpos($url, "/"), strlen($url));
				$imgurl = $urlprefix.$imagename;
	
				echo "			<location>".$imgurl."</location>\n";
				echo "		</track>\n";
			}
		}			
	}
		 
	echo "	</trackList>\n";
	echo "</playlist>\n";
	
	?>