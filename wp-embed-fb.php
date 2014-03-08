<?php
/**
 * @package WP_Embed_Facebook
 * @version 1.2.2
 */
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.saliuitl.org/en/wp-embed-facebook/
Description: Embed a Facebook page, post, event, photo or profile to any Wordpress post or page. Copy any fb url to a single line on your post.
Author: Miguel Sirvent
Version: 1.2.2
Author URI: http://www.saliuitl.org/
*/

/*
 * Global definitions and core include.
 */
load_plugin_textdomain( 'wp-embed-fb', '', WPEMFBLIB.'/lang' );
define('WPEMFDIR',dirname( __FILE__));
define('WPEMFBLIB',WPEMFDIR.'/lib/');
require_once WPEMFBLIB.'core.php';

/*
 * All actions, filters and hooks.
 */
register_activation_hook( __FILE__, array('WP_Embed_FB', 'install') );
register_uninstall_hook( __FILE__, array('WP_Embed_FB', 'uninstall') );
add_action('init',array('WP_Embed_FB','init'));
add_action( 'wp_enqueue_scripts', array('WP_Embed_FB', 'wp_enqueue_scripts') );
add_filter('the_content', array('WP_Embed_FB','the_content'),10,1);

if(is_admin()){
	require_once WPEMFBLIB.'admin.php';
	add_action('admin_menu', array('EmbFbAdmin','add_page'));
}

?>
