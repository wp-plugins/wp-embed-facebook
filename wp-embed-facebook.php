<?php
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.wpembedfb.com
Description: Embed a Facebook video, photo, album, event, page, profile, or post. Copy any facebook url to a single line on your post, or use shortcode [facebook='url' width='' ] more info at <a href="http://www.wpembedfb.com" title="plugin website">www.wpembedfb.com</a>
Author: Miguel Sirvent
Version: 1.8.3
Author URI: http://profiles.wordpress.org/poxtron/
*/

/*
 * Constant definitions 
 */
define("WPEMFBDIR",plugin_dir_path( __FILE__ ));
define("WPEMFBURL",plugin_dir_url( __FILE__ ));
define("WPEMFBSLUG",dirname(plugin_basename(__FILE__)));
// core include
require_once WPEMFBDIR.'lib/core.php';

/*
 * ALL actions, filters and hooks.
 */
register_activation_hook(__FILE__, array('WP_Embed_FB', 'install') );
register_uninstall_hook(__FILE__, array('WP_Embed_FB', 'uninstall') );
register_deactivation_hook(__FILE__, array('WP_Embed_FB', 'deactivate'));
add_action('init',array('WP_Embed_FB','init'),1);
add_action('wp_enqueue_scripts', array('WP_Embed_FB', 'wp_enqueue_scripts') );
add_filter('the_content', array('WP_Embed_FB','the_content'),10,2);
add_shortcode('facebook', array('WP_Embed_FB','shortcode') );
add_action('plugins_loaded',array('WP_Embed_FB','plugins_loaded'));
//optional filter to content anonymous function
if( get_option('wpemfb_fb_root') === 'true' ){
	add_filter('the_content', array('WP_Embed_FB','fb_root'),10,1);
}
	
// wp-admin functions

if(is_admin()){
	require_once WPEMFBDIR.'lib/admin.php';
	add_action('admin_menu', array('EmbFbAdmin','add_page'));
	add_action( 'admin_enqueue_scripts', array('EmbFbAdmin','admin_enqueue_scripts'), 10,1);
	add_action( 'admin_init', array('EmbFbAdmin','admin_init'));
}
//TODO: show like buttons on tiny mce
//add_action( 'after_wp_tiny_mce', 'custom_after_wp_tiny_mce' );


?>