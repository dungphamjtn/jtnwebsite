<?php add_action('admin_menu', 'create_theme_option');   function create_theme_option()   
{     add_theme_page("Tùy chỉnh", "Tùy chỉnh", 'edit_themes', basename(__FILE__), 'my_theme_options');   } 
?>
