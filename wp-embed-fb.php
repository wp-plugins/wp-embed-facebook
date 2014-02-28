<?php
/**
 * @package WP_Embed_FB
 * @version 1.0
 */
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.saliuitl.org/wp-embed-facebook/
Description: This plugin transforms facebook links into graphic content. 
Author: Miguel Sirvent
Version: 1.1
Author URI: http://www.facebook.com/poxtron
*/

add_action('init','wpemfblang');
function wpemfblang(){
	load_plugin_textdomain( 'wp-embed-fb', '', basename( dirname( __FILE__ ) ) . '/lang' );
}

define('WPEMFBLIB',dirname( __FILE__ ) . '/lib/');

require_once WPEMFBLIB.'core.php';

register_activation_hook( __FILE__, array('WP_Embed_FB', 'install') );
//register_uninstall_hook( __FILE__, array('WP_Embed_FB', 'uninstall') );
register_deactivation_hook( __FILE__, array('WP_Embed_FB', 'uninstall'));
add_action('init',array('FaceInit','init'));

add_action( 'wp_enqueue_scripts', array('WP_Embed_FB', 'wp_enqueue_scripts') );

add_filter('the_content', array('WP_Embed_FB','the_content'),10,1);

if(is_admin()){
	require_once WPEMFBLIB.'admin.php';
	add_action('admin_menu', array('EmbFbAdmin','add_page'));
}

?>
