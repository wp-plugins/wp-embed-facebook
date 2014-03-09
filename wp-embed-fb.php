<?php
/**
 * @package WP_Embed_Facebook
 * @version 1.3
 */
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.saliuitl.org/en/wp-embed-facebook/
Description: Embed a Facebook page, post, event, photo or profile to any Wordpress post or page. Copy any fb url to a single line on your post, or use shortcode [facebook url='' width='' temp='']
Author: Miguel Sirvent
Version: 1.3
Author URI: http://www.saliuitl.org/
*/

/*
 * Global definitions and core include.
 */
define('WPEMFBDIR',dirname(__FILE__));
require_once WPEMFBDIR.'/lib/core.php';

/*
 * All actions, filters and hooks.
 */
register_activation_hook(__FILE__, array('WP_Embed_FB', 'install') );
register_uninstall_hook(__FILE__, array('WP_Embed_FB', 'uninstall') );
add_action('init',array('WP_Embed_FB','init'));
add_action('wp_enqueue_scripts', array('WP_Embed_FB', 'wp_enqueue_scripts') );
add_filter('the_content', array('WP_Embed_FB','fb_scripts'),10,1);
add_filter('the_content', array('WP_Embed_FB','the_content'),10,2);
add_shortcode('facebook', array('WP_Embed_FB','shortcode') );
if(is_admin()){
	require_once WPEMFBDIR.'/lib/admin.php';
	add_action('admin_menu', array('EmbFbAdmin','add_page'));
	add_action('admin_init', array('EmbFbAdmin','admin_init'));
	
}

?>
