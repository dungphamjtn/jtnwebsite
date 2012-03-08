<?php
function image_from_description($data) {
		preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);
		return $matches[1][0];
}


function select_image($img, $size) {
	$img = explode('/', $img);
	$filename = array_pop($img);

	$s = array(
		'_s.', // square
		'_t.', // thumb
		'_m.', // small
		'.',   // medium
		'_b.'  // large
	);

	$img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
	return implode('/', $img);
	}
	?>