<?php
/*
 * Main Class of the plugin.
 */
class WP_Embed_FB {
	static $fbsdk;
	static $width = '';
	static $height = '';
	static $theme = '';
	/*
	 * Save default values to data base
	 */
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			if ( !is_multisite() ) {
				$opt = get_option($option);
				if($opt === false)
					update_option($option, $value);
			} 
			else { // TODO: multiple fb apps for each site probably not needed for public data
				$opt = get_option($option);
				if($opt === false)
			    	update_site_option($option, $value);  
			}			
		}
		return;		
	}
	/*
	 * Delete all plugin options on uninstall
	 */
	static function uninstall(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option ) {
			if ( !is_multisite() ) {
				delete_option($option);
			} 
			else { // TODO: multiple fb apps for each site
			    delete_site_option($option);  
			}			
		}
		return;		
	}
	/*
	 * Default options
	 */	
	static function getdefaults(){
		return array(
						'wpemfb_max_width' 		=> '600',
						'wpemfb_enqueue_style' 	=> 'true',
						'wpemfb_app_id' 		=> '0',
						'wpemfb_app_secret'		=> '0',
						'wpemfb_proportions' 	=> 0.36867,
						'wpemfb_height'			=> '221.202',
						'wpemfb_show_like'		=> 'true',
						'wpemfb_fb_root'		=> 'true',
						'wpemfb_theme'			=> 'default',
						);
	}
	/*
	 * load translations and facebook sdk
	 */
	static function init(){
		load_plugin_textdomain( 'wp-embed-fb', '', WPEMFBLIB.'/lang' );
		FaceInit::init();
	}
	/*
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts(){
		$theme = get_option('wpemfb_theme');
        wp_register_style( 'wpemfb-style', plugins_url('/wp-embed-facebook/templates/'.$theme.'/wpemfb.css'));
        wp_enqueue_style( 'wpemfb-style' );		
	}
	/*
	 * the_content filter to process fb url's
	 */
	static function the_content($the_content){
		preg_match_all("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", $the_content, $matches, PREG_SET_ORDER);
		if(!empty($matches) && is_array($matches)){
			self::$fbsdk = FaceInit::$fbsdk;
			if(self::$fbsdk !== 'unactive'){
				foreach($matches as $match) {
			    	$the_content = preg_replace("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", self::fb_embed($match), $the_content, 1);
			    }
			}
		}
		return $the_content;		
	}
	/**
	 * Extract fb_id from the url
	 * @param array $match[2]=
	 */
	static function fb_embed($match){ //TODO: photos!
		$vars = array();
		parse_str(parse_url($match[2], PHP_URL_QUERY), $vars);
		if(isset($vars['fbid'])){ //for photos deprecated by fb 
			$fb_id = $vars['fbid'];
		} else {
			$url = explode('?', $match[2]);
			$clean = explode('/', $url[0]);
			$end = end($clean);	
			if(empty($end)){
				array_pop($clean);
			} 
			$fb_id = end($clean);
			if( $key = array_search('posts',$clean) !== false ){
				$user = $clean[$key -1];
				$post = $clean[$key +1];
				$fb_data = array('user' => $user ,'is_post' => $post);
				return self::print_fb_data($fb_data);				
			} 	
		}
		return self::fb_api_get($fb_id, $match[2]);
	}
	/*
	 * get data from fb using $fbsdk->api('/'.$fb_id) :)
	 */
	static function fb_api_get($fb_id, $url){
		$wp_emb_fbsdk = self::$fbsdk;
		try {
			$fb_data = $wp_emb_fbsdk->api('/'.$fb_id);
			//$res = '<pre>'.print_r($fb_data,true).'</pre>'; //to inspect what elements are queried by default
			$res = self::print_fb_data($fb_data);
		} catch(FacebookApiException $e) {
			$res = '<p><a href="http://wwww.facebook.com/'.$url.'" target="_blank" rel="nofollow">http://wwww.facebook.com/'.$url.'</a>';
			//uncoment this lines to debug
			//if(is_super_admin()){
				//$res .= '<span style="color: red">'.__('This facebook link is not public', 'wp-embed-fb').'</span></p>';
				//$res .= print_r($e->getResult(),true); 
				//$res .= 'fb_id'.$fb_id;
			//}
				 
		}
		return $res;		
	}
	/*
	 * find out what kind of data we got from facebook
	 */
	static function print_fb_data($fb_data){
		if(empty(self::$width)){
			$width = get_option('wpemfb_max_width');
			$height = get_option('wpemfb_height');
		} else {
			$width = self::$width;
			$height = self::$height;
		}
		$prop = get_option('wpemfb_proportions');
		if(isset($fb_data['start_time'])) { //is event
			$template = self::locate_template('event');
		//} elseif(isset($fb_data['source'])) { //is photo Deprecated by facebook
			//$template = self::locate_template('photo');
		} elseif(isset($fb_data['category'])) { //is a page
			if(isset($fb_data['is_community_page']) && $fb_data['is_community_page'] == "1" ){
				$template = self::locate_template('com-page'); //is community page
			}else {
				$template = self::locate_template('page');
				/**
				 * To add a new template for a fb category add something like this to your functions.php file
				 *  
				 * add_action( 'wpemfb_category_template', 'your_function', 10, 1 );
				 * function your_fuction($category) {
				 * 		if($category == 'Museum/art gallery'){
				 * 			$template = WP_Embed_FB::locate_template('museum')
				 * 		}
				 * }
				 * 
				 * then create a file named museum.php inside your-theme/plugins/wp-embed-facebook/default/
				 * remember you can use print_r($fb_data) to see all facebook data.
				 */
				do_action('wpemfb_category_template',$fb_data['category']);
			}
		} elseif(isset($fb_data['is_post'])) {
			$template = self::locate_template('posts');
		} elseif(isset($fb_data['width'])) {
			$template = self::locate_template('photo');
		}else { //is profile
			$template = self::locate_template('profile');
		}
		ob_start();
			include($template);
		return ob_get_clean();
	}
	/*
	 * locate the proper template to show the embed
	 */
	static function locate_template($template_name){
		$theme = get_option('wpemfb_theme');
		$located = locate_template(array('plugins/wp-embed-facebook/'.$theme.'/'.$template_name.'.php'));
		if(empty($located))
			$located =  WPEMFBDIR.'/templates/'.$theme.'/'.$template_name.'.php';
		return $located;
	}
	/*
	 * Formatting functions.
	 */ 
	/*
	 * If a user has a lot of websites registered on fb this function will only link to the first one 
	 */
	static function getwebsite($urls){
		$url = explode(' ',$urls);
		$clean = explode('?', $url[0]);
		$cleaner = str_replace(array('http://', 'https://'), array('',''), $clean[0]);
		$ret = '<a href="http://'.$cleaner.'" title="'.__('Web Site', 'wp-embed-fb').'" target="_blank">'.__('Web Site','wp-embed-fb').'</a>';
	return $ret;
	}
	/*
	 * Translate fb categories to current locale
	 */
	static function fb_categories($category){
		$fbcats = array(	
							__('Museum/art gallery') ,
							__('Local business'),
							__('Concert venue'),
							__('Public places'),
						);
		//$catsflip = array_flip($fbcats); TODO: Translate categories
		if($id = array_search($category, $fbcats) !== false)
			echo $fbcats[$id];
		else
			echo (string)$category;
		return;
		//$replace = array('Museo - Galería de Arte','Negocio Local','Sala de Conciertos','Espacio público');
	}
	/*
	 * facebook scripts required to show like buttons and posts added on top of the content
	 */
	static function fb_scripts($the_content){
		$opt = get_option('wpemfb_fb_root');
		if($opt === 'true'){
			ob_start();
			?>
			<div id="fb-root"></div>
			<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/<?php echo get_locale() ?>/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
			<?php
			$scripts = ob_get_clean();
			return $scripts.$the_content;					
		}		
		return $the_content;		
	}
	/*
	 * show like buttons or like count
	 */
	static function like_btn($fb_id,$likes=null,$share=false,$faces=false){
		$opt = get_option('wpemfb_show_like');
		if($opt === 'true') :
			ob_start();
			?>
				<div class="fb-like" data-href="https://facebook.com/<?php echo $fb_id ?>" data-layout="button_count" data-action="like" data-show-faces="<?php echo $faces ? 'true' : 'false' ?>" data-share="<?php echo $share ? 'true' : 'false' ?>" ></div>		
			<?php
			ob_end_flush();
			return; 
		else :
			printf( __( '%d people like this.', 'wp-embed-fb' ), $likes );
			return;
		endif;			
	}
	/**
	 * Shotcode function
	 * [facebook='url' width='600'] width is optional
	 * @param array [0]=>url ['width']=>embed width 
	 */ 
	static function shortcode($atts){
		if(!empty($atts) && isset($atts[0])){
			$url = '<p>'.trim($atts[0],'=').'</p>';
			if(isset($atts['width'])){
				$prop = get_option('wpemfb_proportions');
				self::$width = $atts['width'];
				self::$height = $prop * $atts['width'];
			}
			$embed = self::the_content($url);
			self::$height = '';
			self::$width = '';
			self::$theme = '';
			return $embed;
		}
		return;
	}
	static function shortcode_atts($atts){
		if(isset($atts['width'])){
			$width = $atts['width'];
			$prop = get_option('wpemfb_proportions');
			$height = $width * $prop;
		}
		if(isset($atts['theme']))
			$temp = $atts['theme'];		
	}
}
/*
 * Trigering the FaceInit::init(); will give you access to the fb php sdk on FaceInit::$fbsdk which you can use to make any call shown here
 * https://developers.facebook.com/docs/reference/php/ 
 */	
class FaceInit {
	static $fbsdk;
	static function init(){
		if(!class_exists('Facebook'))
			require('fb/facebook.php');
		$config = array();
		$config['appId'] = get_option('wpemfb_app_id');
		$config['secret'] = get_option('wpemfb_app_secret');
		$config['fileUpload'] = false; // optional
		if($config['appId'] != '0' && $config['secret'] != '0' )
			self::$fbsdk = new Facebook($config);
		else
			self::$fbsdk = 'unactive';
	}
}
?>