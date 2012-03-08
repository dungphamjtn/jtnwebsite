<?php


//Define constants:
define('RM_FUNCTIONS', TEMPLATEPATH . '/functions/');
define('RM_INCLUDES', TEMPLATEPATH . '/includes/');
define('RM_LANG', TEMPLATEPATH . '/lang/');
define('RM_THEME', 'Mighty');
define('RM_THEME_DIR', get_bloginfo('template_directory'));
define('RM_THEME_DOCS', RM_THEME_DIR.'/functions/docs/docs.pdf');
define('RM_THEME_LOGO', RM_THEME_DIR.'/functions/img/logo.png');
define('RM_MAINMENU_NAME', 'general-options');
define('RM_SHORT', 'mighty_');
define('RM_THEME_VERSION', '1.0');
define('RM_WIDGETS', TEMPLATEPATH . '/widgets/');


//Load all-purpose functions:
	require_once (RM_FUNCTIONS . 'custom-functions.php');
	require_once (RM_FUNCTIONS . 'comment-list.php');
	require_once (RM_FUNCTIONS . 'shortcodes.php');
	require_once (RM_FUNCTIONS . 'dynamic-functions.php');
	
//Load widgets:
	require_once (RM_FUNCTIONS . 'register-widgets.php');
	require_once (RM_WIDGETS . 'widget-flickr.php');
	require_once (RM_WIDGETS . 'widget-popular.php');
	require_once (RM_WIDGETS . 'widget-tabbed.php');


//Load admin specific files:
if (is_admin()) :
	require_once (RM_FUNCTIONS . 'meta-boxes.php');
	require_once (RM_FUNCTIONS . 'admin-helper.php');
	require_once (RM_FUNCTIONS . 'ajax-image.php');
	require_once (RM_FUNCTIONS . 'generate-options.php');
	require_once (RM_FUNCTIONS . 'include-options.php');
endif;



//Add admin styles/scripts:
add_action('admin_head', 'rm_admin_head');


?>