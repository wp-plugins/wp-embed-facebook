<?php
/**
 * @package WP_Embed_Facebook
 * @version 1.6.2
 */
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.wpembedfb.com
Description: Embed a Facebook video, photo, album, event, page, profile, or post. Copy any facebook url to a single line on your post, or use shortcode [facebook='url' width='' ] more info @ <a href="http://www.wpembedfb.com" title="plugin website">www.wpembedfb.com</a>
Author: Miguel Sirvent
Version: 1.6.2
Author URI: http://profiles.wordpress.org/poxtron/
*/

/*
 * Constant definitions 
 */
define("WPEMFBSLUG",dirname(plugin_basename(__FILE__)));

// core include
require_once WP_PLUGIN_DIR."/".WPEMFBSLUG.'/lib/core.php';

/*
 * All actions, filters and hooks.
 */
register_activation_hook(__FILE__, array('WP_Embed_FB', 'install') );
register_uninstall_hook(__FILE__, array('WP_Embed_FB', 'uninstall') );
add_action('init',array('WP_Embed_FB','init'),1);
add_action('wp_enqueue_scripts', array('WP_Embed_FB', 'wp_enqueue_scripts') );
add_filter('the_content', array('WP_Embed_FB','fb_scripts'),10,1);
add_filter('the_content', array('WP_Embed_FB','the_content'),10,2);
add_shortcode('facebook', array('WP_Embed_FB','shortcode') );

// wp-admin functions
if(is_admin()){
	require_once WP_PLUGIN_DIR."/".WPEMFBSLUG.'/lib/admin.php';
	add_action('admin_menu', array('EmbFbAdmin','add_page'));
}

?>
